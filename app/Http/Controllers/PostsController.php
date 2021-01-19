<?php

namespace App\Http\Controllers;

use App\Events\BlogPostPosted;
use App\Http\Requests\StorePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

// use Illuminate\Support\Facades\Redis as Cache;
// use Illuminate\Support\Facades\Redis;

// [
//     'show' => 'view',
//     'create' => 'create',
//     'store' => 'create',
//     'edit' => 'update',
//     'update' => 'update',
//     'destroy' => 'delete',
// ]
class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(
            'posts.index',
            [
                'posts' => BlogPost::latestWithRelations()->get(),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return view('posts.show', [
        //     'post' => BlogPost::with(['comments' => function ($query) {
        //         return $query->latest();
        //     }])->findOrFail($id),
        // ]);
        $blogPost = Cache::remember("blog-post-{$id}", 60, function () use ($id) {
            return BlogPost::with('comments', 'tags', 'user', 'comments.user')
                ->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if (
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::forever($usersKey, $usersUpdate);

        if (!Cache::has($counterKey)) {
            Cache::forever($counterKey, 1);
        } else {
            Cache::increment($counterKey, $diffrence);
        }

        $counter = Cache::get($counterKey);

        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter,
        ]);
    }

    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $blogPost = BlogPost::create($validatedData);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');

            $blogPost->image()->save(
                Image::make(['path' => $path])
            );
        }

        event(new BlogPostPosted($blogPost));

        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);

        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize($post);

        $validatedData = $request->validated();
        $post->fill($validatedData);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');

            if ($post->image) {
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            } else {
                $post->image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if (Gate::denies('delete-post', $post)) {
        //     abort(403, "You can't delete this blog post!");
        // }
        $this->authorize($post);

        $post->delete();

        // BlogPost::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }














    // public function __construct()
    // {
    //     $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    // }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     // $mostCommented = Cache::tags(['blog-post'])->remember('mostCommented', 60, function () {
    //     //     return BlogPost::mostCommented()->take(5)->get();
    //     // });
    //     $mostCommented = Cache::remember('mostCommented', 60, function () {
    //         return BlogPost::mostCommented()->take(5)->get();
    //     });

    //     $mostActive = Cache::remember('mostActive', 60, function () {
    //         return User::withMostBlogPosts()->take(5)->get();
    //     });

    //     $mostActiveLastMonth = Cache::remember('mostActiveLastMonth', 60, function () {
    //         return User::withMostBlogPostsLastMonth()->take(5)->get();
    //     });

    //     return view(
    //         'posts.index',
    //         [
    //             'posts' => BlogPost::latestWithRelations()->get(),
    //             'mostCommented' => $mostCommented,
    //             'mostActive' => $mostActive,
    //             'mostActiveLastMonth' => $mostActiveLastMonth,
    //         ]
    //     );
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param int $id
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     // return view('posts.show', [
    //     //     'post' => BlogPost::with(['comments' => function ($query) {
    //     //         return $query->latest();
    //     //     }])->findOrFail($id),
    //     // ]);
    //     // $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function () use ($id) {
    //     $blogPost = Cache::remember("blog-post-{$id}", 60, function () use ($id) {
    //         return BlogPost::with('comments', 'tags', 'user', 'comments.user')->findOrFail($id);
    //     });

    //     $sessionId = session()->getId();
    //     $counterKey = "blog-post-{$id}-counter";
    //     $usersKey = "blog-post-{$id}-users";

    //     $users = Cache::get($usersKey, []);
    //     $usersUpdate = [];
    //     $diffrence = 0;
    //     $now = now();

    //     foreach ($users as $session => $lastVisit) {
    //         if ($now->diffInMinutes($lastVisit) >= 1) {
    //             $diffrence--;
    //         } else {
    //             $usersUpdate[$session] = $lastVisit;
    //         }
    //     }

    //     if (
    //         !array_key_exists($sessionId, $users)
    //         || $now->diffInMinutes($users[$sessionId]) >= 1
    //     ) {
    //         $diffrence++;
    //     }

    //     $usersUpdate[$sessionId] = $now;
    //     // Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
    //     Cache::forever($usersKey, $usersUpdate);

    //     // if (!Cache::tags(['blog-post'])->has($counterKey)) {
    //     //     Cache::tags(['blog-post'])->forever($counterKey, 1);
    //     // } else {
    //     //     Cache::tags(['blog-post'])->increment($counterKey, $diffrence);
    //     // }

    //     // $counter = Cache::tags(['blog-post'])->get($counterKey);

    //     if (!Cache::has($counterKey)) {
    //         Cache::forever($counterKey, 1);
    //     } else {
    //         Cache::increment($counterKey, $diffrence);
    //     }

    //     $counter = Cache::get($counterKey);

    //     return view('posts.show', [
    //         'post' => $blogPost,
    //         'counter' => $counter,
    //     ]);
    // }

    // public function create()
    // {
    //     // $this->authorize('posts.create');
    //     return view('posts.create');
    // }

    // public function store(StorePost $request)
    // {
    //     $validatedData = $request->validated();
    //     $validatedData['user_id'] = $request->user()->id;
    //     $blogPost = BlogPost::create($validatedData);
    //     $request->session()->flash('status', 'Blog post was created!');

    //     return redirect()->route('posts.show', ['post' => $blogPost->id]);
    // }

    // public function edit($id)
    // {
    //     $post = BlogPost::findOrFail($id);
    //     $this->authorize($post);

    //     return view('posts.edit', ['post' => $post]);
    // }

    // public function update(StorePost $request, $id)
    // {
    //     $post = BlogPost::findOrFail($id);

    //     // if (Gate::denies('update-post', $post)) {
    //     //     abort(403, "You can't edit this blog post!");
    //     // }
    //     $this->authorize($post);

    //     $validatedData = $request->validated();

    //     $post->fill($validatedData);
    //     $post->save();
    //     $request->session()->flash('status', 'Blog post was updated!');

    //     return redirect()->route('posts.show', ['post' => $post->id]);
    // }

    // public function destroy(Request $request, $id)
    // {
    //     $post = BlogPost::findOrFail($id);

    //     // if (Gate::denies('delete-post', $post)) {
    //     //     abort(403, "You can't delete this blog post!");
    //     // }
    //     $this->authorize($post);

    //     $post->delete();

    //     // BlogPost::destroy($id);

    //     $request->session()->flash('status', 'Blog post was deleted!');

    //     return redirect()->route('posts.index');
    // }
}











// namespace App\Http\Controllers;

// use App\Http\Requests\StorePost;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Gate;

// use App\Models\BlogPost;
// use App\Models\User;
// use Illuminate\Support\Facades\Cache;

// class PostsController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth')
//             ->only(['create', 'store', 'edit', 'update', 'destroy']);
//     }


//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index()
//     {
//         $mostCommented = Cache::remember('blog-post-commented', now()->addSeconds(30), function () {
//             return BlogPost::mostCommented()->take(5)->get();
//         });

//         $mostActive = Cache::remember('users-most-active', 30, function () {
//             return User::withMostBlogPosts()->take(5)->get();
//         });

//         $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 30, function () {
//             return User::withMostBlogPostsLastMonth()->take(5)->get();
//         });

//         return view(
//             'posts.index',
//             [
//                 'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(),
//                 'mostCommented' => $mostCommented,
//                 'mostActive' => $mostActive,
//                 'mostActiveLastMonth' => $mostActiveLastMonth,
//             ]
//         );

//         // DB::enableQueryLog();

//         // // $posts = BlogPost::all();
//         // $posts = BlogPost::with('comments')->get();

//         // foreach ($posts as $post) {
//         //     foreach ($post->comments as $comment) {
//         //         echo $comment->content;
//         //     }
//         // }

//         // dd(DB::getQueryLog());
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function create()
//     {
//         return view('posts.create');
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(StorePost $request)
//     {
//         // $validated = $request->validated();

//         // $post = new BlogPost();

//         // $post->title = $validated['title'];
//         // $post->content = $validated['content'];
//         // $post->save();

//         // $request->session()->flash('status', 'The blog post was created!');

//         // return redirect()->route('posts.show', ['post' => $post->id]);


//         $validatedData = $request->validated();
//         $validatedData['user_id'] = $request->user()->id;

//         $blogPost = BlogPost::create($validatedData);
//         $request->session()->flash('status', 'Blog post was created!');

//         return redirect()->route('posts.show', ['post' => $blogPost->id]);
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  \App\Models\BlogPost  $blogPost
//      * @return \Illuminate\Http\Response
//      */
//     public function show($id)
//     {
//         // return view('posts.show', [
//         //     'post' => BlogPost::with(['comments' => function ($query) {
//         //         return $query->latest();
//         //     }])->findOrFail($id),
//         // ]);

//         $blogPost = Cache::remember("blog-post-{$id}", 60, function () use ($id) {
//             return BlogPost::with('comments')->findOrFail($id);
//         });

//         $sessionId = session()->getId();
//         $counterKey = "blog-post-{$id}-counter";
//         $usersKey = "blog-post-{$id}-users";

//         $users = Cache::get($usersKey, []);
//         $usersUpdate = [];
//         $difference = 0;
//         $now = now();

//         foreach ($users as $session => $lastVisit) {
//             if ($now->diffInMinutes($lastVisit) >= 1) {
//                 $difference--;
//             } else {
//                 $usersUpdate[$session] = $lastVisit;
//             }
//         }

//         if (!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= 1) {
//             $difference++;
//         }

//         $usersUpdate[$sessionId] = $now;
//         Cache::forever($usersKey, $usersUpdate);

//         if (!Cache::has($counterKey)) {
//             Cache::forever($counterKey, 1);
//         } else {
//             Cache::increment($counterKey, $difference);
//         }

//         $counter = Cache::get($counterKey);

//         return view('posts.show', [
//             'post' => $blogPost,
//             'counter' => $counter,
//             // 'mostCommented' => BlogPost::mostCommented()->take(5)->get(),
//         ]);
//     }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  \App\Models\BlogPost  $blogPost
//      * @return \Illuminate\Http\Response
//      */
//     public function edit($id)
//     {
//         // return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);

//         $post = BlogPost::findOrFail($id);

//         // if (Gate::denies('update-post', $post)) {
//         //     abort(403, "You can't edit this blog post");
//         // }
//         // $this->authorize('update', $post);
//         $this->authorize($post);

//         return view('posts.edit', ['post' => $post]);
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\BlogPost  $blogPost
//      * @return \Illuminate\Http\Response
//      */
//     public function update(StorePost $request, $id)
//     {
//         $post = BlogPost::findOrFail($id);

//         // if (Gate::denies('update-post', $post)) {
//         //     abort(403, "You can't edit this blog post");
//         // }
//         // $this->authorize('update', $post);
//         $this->authorize($post);

//         $validated = $request->validated();
//         $post->fill($validated);

//         $post->save();

//         $request->session()->flash('status', 'Blog post has been updated');

//         return redirect()->route('posts.show', ['post' => $post->id]);
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\BlogPost  $blogPost
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(Request $request, $id)
//     {
//         $post = BlogPost::findOrFail($id);

//         // if (Gate::denies('update-post', $post)) {
//         //     abort(403, "You can't delete this blog post");
//         // }
//         // $this->authorize('delete', $post);
//         $this->authorize($post);

//         $post->delete();

//         $request->session()->flash('status', 'Blog post has been deleted!');

//         return redirect()->route('posts.index');
//     }
// }
