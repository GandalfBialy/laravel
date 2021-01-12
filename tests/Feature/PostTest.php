<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\BlogPost;

// use Illuminate\Foundation\Testing\WithoutMiddleware;

class PostTest extends TestCase
{
    // use WithoutMiddleware;
    use RefreshDatabase;

    private function createDummyBlogPost($userId = null): BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Content of the blog post';
        // $post->save();
        // return $post;

        // return factory(BlogPost::class)->states('new-title')->create(
        //     [
        //         'user_id' => $userId ?? $this->user()->id,
        //     ]
        // );
    }

    public function testNoPostsWhenDatabaseIsEmpty()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No posts found!');
    }

    public function testSee1PostWhenThereIs1()
    {
        // Arrange
        $title = "Test title";
        $content = "Some content of the post";

        $post = new BlogPost();
        $post->title = $title;
        $post->content = $content;
        $post->save();

        // Act
        $response = $this->get('posts');

        // Assert
        $response->assertSeeText($title);

        $this->assertDatabaseHas('blog_posts', [
            'title' => $title,
            'content' => $content,
        ]);
    }

    public function testStoreValid()
    {
        // $this->withoutMiddleware();

        $parameters = [
            'title' => 'Valid title',
            'content' => 'some valid content of post',
        ];

        $this->post('/posts', $parameters)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), "The blog post was created!");
    }

    // public function testStoreFail()
    // {
    //     $params = [
    //         'title' => 'x',
    //         'content' => 'x',
    //     ];

    //     // $this->actingAs($this->user())
    //     //     ->post('/posts', $params)
    //     //     ->assertStatus(302)
    //     //     ->assertSessionHas('errors');

    //     $messages = session('errors')->getMessages();

    //     $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
    //     $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    // }

    // public function testUpdateValid()
    // {
    //     $user = $this->user();
    //     $post = $this->createDummyBlogPost($user->id);

    //     $this->assertDatabaseHas('blog_posts', $post->toArray());

    //     $params = [
    //         'title' => 'A new named title',
    //         'content' => 'Content was changed',
    //     ];

    //     $this->actingAs($user)
    //         ->put("/posts/{$post->id}", $params)
    //         ->assertStatus(302)
    //         ->assertSessionHas('status');

    //     $this->assertEquals(session('status'), 'Blog post was updated!');
    //     $this->assertDatabaseMissing('blog_posts', $post->toArray());
    //     $this->assertDatabaseHas('blog_posts', [
    //         'title' => 'A new named title',
    //     ]);
    // }

    // public function testDelete()
    // {
    //     $user = $this->user();
    //     $post = $this->createDummyBlogPost($user->id);
    //     $this->assertDatabaseHas('blog_posts', $post->toArray());

    //     $this->actingAs($user)
    //         ->delete("/posts/{$post->id}")
    //         ->assertStatus(302)
    //         ->assertSessionHas('status');

    //     $this->assertEquals(session('status'), 'Blog post was deleted!');
    //     // $this->assertDatabaseMissing('blog_posts', $post->toArray());
    //     $this->assertSoftDeleted('blog_posts', $post->toArray());
    // }
}
