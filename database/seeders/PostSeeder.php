<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users      = User::all();
        $categories = Category::all();
        $tags       = Tag::all();

        for ($i = 1; $i <= 20; $i++) {
            $title = "Sample Post Number {$i}";

            $post = Post::create([
                'user_id'      => $users->random()->id,
                'category_id'  => $categories->random()->id,
                'title'        => $title,
                'slug'         => Str::slug($title),
                'excerpt'      => "This is a short excerpt for post {$i}.",
                'body'         => "This is the full body content of post {$i}. " . str_repeat("Lorem ipsum dolor sit amet. ", 10),
                'status'       => collect(['draft', 'published', 'archived'])->random(),
                'views'        => rand(0, 1000),
                'published_at' => now()->subDays(rand(1, 90)),
            ]);

            // Attach 1–3 random tags to each post
            $post->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}