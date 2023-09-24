<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::create([
             'name' => 'admin',
             'email' => 'admin@admin.com',
             'password' => bcrypt(123456),
         ]);

        \App\Models\User::create([
            'name' => 'admin2',
            'email' => 'admin2@admin.com',
            'password' => bcrypt(123456),
        ]);

        Post::factory(5)->create();
        Comment::factory(5)->create();
    }
}
