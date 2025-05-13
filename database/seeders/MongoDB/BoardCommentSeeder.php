<?php

namespace Database\Seeders\MongoDB;

use App\Models\MongoDB\BoardComment;
use App\Models\MongoDB\BoardPost;
use Illuminate\Database\Seeder;

class BoardCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = BoardPost::all();

        foreach ($posts as $post) {
            // 60% 확률로 댓글 있음
            if (rand(1, 100) > 60) {
                continue;
            }

            BoardComment::factory()
                ->count(rand(1, 5))
                ->state([
                    'config_id' => $post->_id,
                    'post_id' => $post->_id,
                ])
                ->create();
        }
    }
}
