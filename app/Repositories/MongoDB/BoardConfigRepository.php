<?php

namespace App\Repositories\MongoDB;

use App\Models\MongoDB\BoardConfig;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BoardConfigRepository
{
    public const PER_PAGE = 10;
    protected BoardConfig $boardConfig;

    public function __construct()
    {
        $this->boardConfig = new BoardConfig();
    }

    /**
     * board_configs 다큐먼트 조회
     *
     * @param   array    $where    검색 조건
     * @param   array    $orderBy  [['created_at', 'desc'], [], ....]
     *
     * @return  Builder
     */
    public function getList(array $where, array $orderBy): Builder
    {
        return $this->boardConfig::query()
            ->when(!empty($where), function ($builder) use ($where) {
                $builder->where($where);
            })
            ->when(!empty($orderBy), function ($builder) use ($orderBy) {
                foreach ($orderBy as [$column, $direction]) {
                    $builder->orderBy($column, $direction);
                }
            });
    }

    public function getConfigList(array $where=[], array $orderBy=[]): Collection
    {
        return $this->getList($where, $orderBy)->get();
    }

    public function getPageList(array $where=[], array $orderBy=[]): LengthAwarePaginator
    {
        return $this->getList($where, $orderBy)->paginate(self::PER_PAGE);
    }

    public function insert(array $data)
    {
        $this->boardConfig->insert($data);
    }
}
