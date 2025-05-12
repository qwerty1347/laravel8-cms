<?php

namespace App\Repositories\MongoDB;

use App\Models\MongoDB\BoardConfig;

class BoardConfigRepository
{
    protected BoardConfig $boardConfig;

    public function __construct()
    {
        $this->boardConfig = new BoardConfig();
    }

    public function insert(array $data)
    {
        $this->boardConfig->insert($data);
    }
}
