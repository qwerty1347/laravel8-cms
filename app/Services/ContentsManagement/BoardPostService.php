<?php

namespace App\Services\ContentsManagement;

use Exception;
use App\Repositories\MongoDB\BoardConfigRepository;
use App\Repositories\MongoDB\BoardPostRepository;

class BoardPostService
{
    protected BoardConfigRepository $boardConfigRepository;
    protected BoardPostRepository $boardPostRepository;

    public function __construct()
    {
        $this->boardConfigRepository = new BoardConfigRepository();
        $this->boardPostRepository = new BoardPostRepository();
    }

    public function index(array $request)
    {
        $where = !empty($request['config']) ? ['config_name' => $request['config']] : ['config_name' => 'all'];

        if (!empty($request['st']) && !empty($request['si'])) {
            $where = [$request['st'] => new \MongoDB\BSON\Regex('^' . preg_quote(trim($request['si']), '/'), 'i')];
        }

        try {
            return view('admin.cms.board.post.index', [
                'configList' => $this->boardConfigRepository->getConfigList(),
                'list'       => $this->boardPostRepository->getPageList($where ?? [], [['_id', 'desc']]),
            ]);
        }
        catch (Exception $e) {
            $logMessage = $e->getMessage()." | FILE: ".$e->getFile()." | LINE: ".$e->getLine();
            logMessage('admin', 'error', $logMessage);
            abort('500');
        }
    }
}
