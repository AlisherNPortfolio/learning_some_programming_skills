<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate('posts');
        $posts = Post::factory(3)
            // ->has(Comment::factory(3), 'comments')
            ->create();

        // $posts->each(function (Post $post) {
        //     // $post->users()->sync([Factory])
        // });
    }
}
