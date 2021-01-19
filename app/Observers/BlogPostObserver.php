<?php

namespace App\Observers;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;

class BlogPostObserver
{
    public function updating(BlogPost $blogPost)
    {
        Cache::forget("blog-post-{$blogPost->id}");
    }

    public function deleting(BlogPost $blogPost)
    {
        // dd('BEING DELETED :((((');
        $blogPost->comments()->delete();
        Cache::forget("blog-post-{$blogPost->id}");
    }

    public function restoring(BlogPost $blogPost)
    {
        $blogPost->comments()->restore();
    }
}
