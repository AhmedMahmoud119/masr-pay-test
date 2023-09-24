<?php

namespace Tests\Unit;


use App\Models\Post;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{

    use RefreshDatabase;

    public function test_create_post()
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::first();
        $data = [
            'title'   => 'test title',
            'content' => 'test content',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/posts', $data);
        $response->assertStatus(201);
    }

    public function test_list_all_posts()
    {
        $this->seed(DatabaseSeeder::class);
        $creator = User::first();
        $this->actingAs($creator);
        $response = $this->get('/api/posts/');
        $response->assertStatus(200);
    }

    public function test_update_post_effect_on_database()
    {
        $this->seed(DatabaseSeeder::class);
        $creator = User::first();
        $this->actingAs($creator);
        $post = Post::first();
        $updatedData = [
            'title'   => 'test title updated',
            'content' => 'test content updated',
        ];
        $response = $this->putJson('/api/posts/' . $post->id, $updatedData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'title'   => 'test title updated',
            'content' => 'test content updated',
        ]);
    }

    public function test_post_validation()
    {
        $this->seed(DatabaseSeeder::class);
        $creator = User::first();
        $this->actingAs($creator);
        $post = Post::first();

        $requestData = [
            //invalid title
            'title'   => null,
            //not valid content
            'content' => null,
        ];
        $DataToValidate = [
            'title',
            'content',
        ];
        $updateResponse = $this->putJson('/api/posts/' . $post->id, $requestData);
        $updateResponse->assertStatus(422);
        $updateResponse->assertJsonValidationErrors($DataToValidate, 'errors');

        $createResponse = $this->postJson('/api/posts/', $requestData);
        $createResponse->assertStatus(422);
        $createResponse->assertJsonValidationErrors($DataToValidate, 'errors');
    }

    public function test_can_delete_posts()
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::first();
        $post = Post::first();

        $response = $this->actingAs($user)->delete('/api/posts/' . $post->id);
        $response->assertStatus(200);

    }

}
