<?php

namespace App\Models;

use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    // use HasFactory, SoftDeletes, Taggable;
    use HasFactory;
    use SoftDeletes;
    use Taggable;

    protected $fillable = [
        'content',
        'user_id',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function (Comment $comment) {
    //         if ($comment->commentable_type === BlogPost::class) {
    //             Cache::forget("blog-post-{$comment->commentable_id}");
    //             Cache::forget("mostCommented");
    //         }
    //     });
    // }
}
