<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Jobs\NotifyUsersPostWasCommented;

class PostCommentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth')->only(['store']);
    // }

    // public function store(BlogPost $post, StoreComment $request)
    // {
    //     $post->comments()->create([
    //         'content' => $request->input('content'),
    //         'user_id' => $request->user()->id,
    //     ]);

    //     $request->session()->flash('status', 'Comment was created');

    //     return redirect()->back();
    // }

    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        // Comment::create()
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        // $request->session()->flash('status', 'Comment has been created!');

        // Mail::to($post->user)->send(
        //     // Mail::to('igorjozefowicz@gmail.com')->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );


        Mail::to($post->user)->queue(
            new CommentPostedMarkdown($comment)
        );

        // NotifyUsersPostWasCommented::dispatch($comment);

        // $when = now()->addMinutes(1);

        // Mail::to('igorjozefowicz@gmail.com')->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );

        return redirect()->back()->withStatus('Comment has been created!');
    }
}
