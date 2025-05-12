<?php

namespace App\Repositories\MongoDB;

use App\Models\MongoDB\BoardPost;

class BoardPostRepository
{
    protected BoardPost $boardPost;

    public function __construct()
    {
        $this->boardPost = new BoardPost();
    }
}
