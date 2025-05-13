<?php

namespace App\Services\ContentsManagement;

use App\Repositories\MongoDB\BoardPostRepository;

class BoardPostService
{
    protected BoardPostRepository $boardPostRepository;

    public function __construct()
    {
        $this->boardPostRepository = new BoardPostRepository();
    }
}
