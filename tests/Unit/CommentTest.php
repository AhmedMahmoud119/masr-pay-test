<?php

namespace Tests\Unit;


use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{

    use RefreshDatabase;

    public function test_create_comment()
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::first();
        $post = Post::first();
        $data = [
            'post_id' => 'test content',
            'content' => 'test content',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/posts/'.$post->id.'/comments', $data);
        $response->assertStatus(201);
    }

    public function test_list_all_posts()
    {
        $this->seed(DatabaseSeeder::class);
        $creator = User::first();
        $post = Post::first();
        $this->actingAs($creator);
        $response = $this->get('/api/posts/'.$post->id.'/comments');
        $response->assertStatus(200);
    }

    public function test_update_comment_effect_on_database()
    {
        $this->seed(DatabaseSeeder::class);
        $creator = User::first();
        $comment = Comment::first();
        $this->actingAs($creator);
        $post = Post::first();
        $updatedData = [
            'content' => 'test content updated',
        ];
        $response = $this->putJson('/api/posts/' . $post->id.'/comments/'.$comment->id, $updatedData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', [
            'content' => 'test content updated',
        ]);
    }

    public function test_comment_validation()
    {
        $this->seed(DatabaseSeeder::class);
        $creator = User::first();
        $this->actingAs($creator);
        $post = Post::first();
        $comment = Comment::first();

        $requestData = [
            //not valid content
            'content' => null,
        ];
        $DataToValidate = [
            'content',
        ];
        $updateResponse = $this->putJson('/api/posts/' . $post->id.'/comments/'.$comment->id, $requestData);
        $updateResponse->assertStatus(422);
        $updateResponse->assertJsonValidationErrors($DataToValidate, 'errors');

        $createResponse = $this->postJson('/api/posts/'. $post->id.'/comments', $requestData);
        $createResponse->assertStatus(422);
        $createResponse->assertJsonValidationErrors($DataToValidate, 'errors');
    }

    public function test_can_delete_comments()
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::first();
        $post = Post::first();
        $comment = Comment::first();

        $response = $this->actingAs($user)->delete('/api/posts/' . $post->id.'/comments/'.$comment->id);
        $response->assertStatus(200);

    }

}
