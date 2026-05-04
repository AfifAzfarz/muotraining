<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $users = User::all();

        foreach ($posts as $post) {
            // Create 2–5 top-level comments per post
            $commentCount = rand(2, 5);

            for ($i = 0; $i < $commentCount; $i++) {
                $comment = Comment::create([
                    'post_id'     => $post->id,
                    'user_id'     => $users->random()->id,
                    'parent_id'   => null,
                    'body'        => "This is a top-level comment on post [{$post->title}].",
                    'is_approved' => (bool) rand(0, 1),
                ]);

                // Add 1 reply to each comment
                Comment::create([
                    'post_id'     => $post->id,
                    'user_id'     => $users->random()->id,
                    'parent_id'   => $comment->id,
                    'body'        => "This is a reply to comment #{$comment->id}.",
                    'is_approved' => true,
                ]);
            }
        }
    }
}