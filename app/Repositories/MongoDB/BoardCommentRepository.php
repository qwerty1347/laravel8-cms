<?php

namespace App\Repositories\MongoDB;

use App\Models\MongoDB\BoardComment;

class BoardCommentRepository
{
    protected BoardComment $boardComment;

    public function __construct()
    {
        $this->boardComment = new BoardComment();
    }
}
