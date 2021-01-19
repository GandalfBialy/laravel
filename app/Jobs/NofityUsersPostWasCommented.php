<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentPostedOnPostWatched;

class NotifyUsersPostWasCommented implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::thatHasCommentedOnPost($this->comment->commentable)
            ->get()
            ->filter(function (User $user) {
                return $user->id !== $this->comment->user_id;
            })->map(function (User $user) {
                Mail::to($user)->send(
                    new CommentPostedOnPostWatched($this->comment, $user)
                );
            });
    }
}




// namespace App\Jobs;

// use App\Mail\CommentPostedOnPostWatched;
// use App\Models\Comment;
// use App\Models\User;
// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;
// use Illuminate\Support\Facades\Mail;

// class NofityUsersPostWasCommented implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public $comment;

//     /**
//      * Create a new job instance.
//      *
//      * @return void
//      */
//     public function __construct(Comment $comment)
//     {
//         $this->comment = $comment;
//     }

//     /**
//      * Execute the job.
//      *
//      * @return void
//      */
//     public function handle()
//     {
//         $now = now();

//         User::thatHasCommentedOnPost($this->comment->commentable)
//             ->get()
//             ->filter(function (User $user) {
//                 return $user->id !== $this->comment->user_id;
//             })
//             ->map(function (User $user) use ($now) {
//                 Mail::to($user)->later(
//                     $now->addSecond(6),
//                     new CommentPostedOnPostWatched($this->comment, $user)
//                 );
//             });
//     }
// }
