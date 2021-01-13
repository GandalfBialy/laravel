<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Comment;
use App\Models\BlogPost;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = BlogPost::all();

        if ($posts->count() === 0) {
            $this->command->info('There are no blog posts -> no comments will be added');
            return;
        }

        $commentsCount = (int)$this->command->ask('How many comments?', 150);
        
        // factory(Comment::class, $commentsCount)->make()->each(function ($comment) use ($posts) {
        //     $comment->blog_post_id = $posts->random()->id;
        //     $comment->save();
        // });
        Comment::factory()->count($commentsCount)->make()->each(function ($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}