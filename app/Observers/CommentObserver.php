<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        if ($comment->commentable_type === BlogPost::class) {
            Cache::forget("blog-post-{$comment->commentable_id}");
            Cache::forget("mostCommented");
        }
    }
}
