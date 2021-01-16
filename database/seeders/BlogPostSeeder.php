<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\BlogPost;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogCount = (int)$this->command->ask('How many blog posts?', 50);
        $users = \App\Models\User::all();
        // $users = User::all();

        // factory(App\BlogPost::class, $blogCount)->make()->each(function($post) use ($users) {
        //     $post->user_id = $users->random()->id;
        //     $post->save();
        // });
        BlogPost::factory()->count($blogCount)->make()->each(function ($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
