<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(20)->create();
        $tags = Tag::factory(10)->create();

        foreach ($users as $user) {
            $posts = Post::factory(rand(2, 8))->create(['user_id' => $user->id]);
            foreach ($posts as $post) {
                Comment::factory(rand(0, 5))->create(['post_id' => $post->id]);
                $post->tags()->attach($tags->random(rand(1, 4))->pluck('id')->toArray());
            }
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
