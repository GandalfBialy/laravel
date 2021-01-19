<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Coment has been posted on your {$this->comment->commentable->title} blog post";
        return $this
            // ->from('admin@laravel.test', 'Admin')
            // ->attach(
            //     storage_path('app/public') . '/' . $this->comment->user->image->path,
            //     [
            //         'as' => 'avatar.jpeg',
            //         'mime' => 'image/jpeg',
            //     ]
            // )
            // ->attachFromStorageDisk('public', $this->comment->user->image->path, 'avatar.jepg')
            // ->attachFromStorage($this->comment->user->image->path, 'avatar.jepg')
            ->attachData(Storage::get($this->comment->user->image->path), 'avatar_data.jpeg', [
                'mime' => 'image/jpeg'
            ])
            ->subject($subject)
            ->view('emails.posts.commented');
    }
}
