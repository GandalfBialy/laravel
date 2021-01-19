<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted as EventsCommentPosted;
use App\Http\Requests\StoreComment;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;

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

        // event(new EventsCommentPosted($comment));

        Mail::to($post->user)->send(
            // Mail::to('wiktorjozefowicz1@gmail.com')->send(
            // new CommentPosted($comment)
            new CommentPostedMarkdown($comment)
        );


        // Mail::to($post->user)->queue(
        //     new CommentPostedMarkdown($comment)
        // );

        // ThrottledMail::dispatch(new CommentPostedMarkdown($comment), $post->user)
        //     ->onQueue('low');
        // NotifyUsersPostWasCommented::dispatch($comment)
        //     ->onQueue('high');

        // $when = now()->addMinutes(1);

        // Mail::to('igorjozefowicz@gmail.com')->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );

        // $request->session()->flash('status', 'Comment has been created!');

        return redirect()->back()->withStatus('Comment has been created!');
    }
}
