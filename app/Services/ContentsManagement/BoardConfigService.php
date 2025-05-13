<?php

namespace App\Services\ContentsManagement;

use Exception;
use Carbon\Carbon;
use App\Repositories\MongoDB\BoardConfigRepository;
use MongoDB\BSON\UTCDateTime;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;


class BoardConfigService
{
    protected BoardConfigRepository $boardConfigRepository;

    public function __construct()
    {
        $this->boardConfigRepository = new BoardConfigRepository();
    }

    public function index(): View
    {
        $list = $this->boardConfigRepository->getList([], [['_id', 'desc']]);

        return view('admin.cms.board.config.index', [
            'list' => $list,
        ]);
    }

    public function store(array $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $accessControl = config('board.access_control_config');

            if (!empty($request['access_control'])) {
                foreach ($request['access_control'] as $item) {
                    $accessControl[$item] = true;
                }
            }

            $this->boardConfigRepository->insert([
                'user_id' => auth()->user()->id,
                'name' => $request['name'],
                'access_control' => $accessControl,
                'created_at' => new UTCDateTime(Carbon::now()),
                'updated_at' => null,
                'deleted_at' => null
            ]);
            
            DB::commit();

            return response()->json(handleSuccessResult());
        }
        catch (Exception $e) {
            DB::rollback();
            $logMessage = $e->getMessage()." | FILE: ".$e->getFile()." | LINE: ".$e->getLine();
            logMessage('adminlog', 'error', $logMessage);

            return response()->json(handleFailureResult(HttpCodeConstant::UNKNOWN_ERROR, $e->getMessage()), HttpCodeConstant::UNKNOWN_ERROR, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
