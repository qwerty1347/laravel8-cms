<?php

namespace App\Services\ContentsManagement;

use App\Repositories\MongoDB\BoardCommentRepository;

class BoardCommentService
{
    protected BoardCommentRepository $boardCommentRepository;

    public function __construct()
    {
        $this->boardCommentRepository = new BoardCommentRepository();
    }
}
