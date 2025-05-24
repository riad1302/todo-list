<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a verified user for all tests
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);
    }

    public function test_it_displays_the_post_index_page()
    {
        Post::factory()->count(3)->create();

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('posts/Index')
            ->has('posts.data', 3)
        );
    }

    public function test_it_filters_posts_by_search()
    {
        Post::factory()->create(['title' => 'Laravel']);
        Post::factory()->create(['title' => 'Symfony']);

        $response = $this->get(route('posts.index', ['search' => 'Lara']));

        $response->assertInertia(fn ($page) => $page->component('posts/Index')
            ->where('filters.search', 'Lara')
            ->has('posts.data', 1)
        );
    }

    public function test_it_displays_the_create_post_form()
    {
        $response = $this->get(route('posts.create'));

        $response->assertInertia(fn ($page) => $page->component('posts/PostForm')
        );
    }

    public function test_it_stores_a_new_post()
    {
        $data = [
            'title' => 'Test Title',
            'content' => 'Test content for the post.',
            'status' => true,
        ];

        $response = $this->post(route('posts.store'), $data);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Test Title']);
    }

    public function test_it_displays_the_edit_post_form()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.edit', $post));

        $response->assertInertia(fn ($page) => $page->component('posts/PostForm')
            ->where('post.id', $post->id)
        );
    }

    public function test_it_updates_an_existing_post()
    {
        $post = Post::factory()->create();

        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'status' => false,
        ];

        $response = $this->put(route('posts.update', $post), $data);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }
}
