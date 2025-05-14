<?php

namespace App\Repositories\MongoDB;

use App\Models\MongoDB\BoardConfig;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BoardConfigRepository
{
    protected BoardConfig $boardConfig;

    public function __construct()
    {
        $this->boardConfig = new BoardConfig();
    }

    /**
     * board_configs 컬렉션 조회
     *
     * @param   array  $where    검색조건
     * @param   array  $orderBy  [['created_at', 'desc'], [], ....]
     *
     * @return  LengthAwarePaginator
     */
    public function getList(array $where, array $orderBy, int $perPage=10): LengthAwarePaginator
    {
        $builder = $this->boardConfig::query()
            ->when(!empty($where), function ($builder) use ($where) {
                $builder->where($where);
            })
            ->when(!empty($orderBy), function ($builder) use ($orderBy) {
                foreach ($orderBy as [$column, $direction]) {
                    $builder->orderBy($column, $direction);
                }
            });

        return $builder->paginate($perPage);
    }

    public function insert(array $data)
    {
        $this->boardConfig->insert($data);
    }
}
