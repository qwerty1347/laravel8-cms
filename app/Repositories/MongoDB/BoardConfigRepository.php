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
     * [getList description]
     *
     * @param   array  $where    [$where description]
     * @param   array  $orderBy  [['created_at', 'desc'], [], ....]
     *
     * @return  LengthAwarePaginator
     */
    public function getList(array $where, array $orderBy, int $perPage=10): LengthAwarePaginator
    {
        $obj = $this->boardConfig::query();

        $obj->when(!empty($where), function ($builder) use ($where) {
            $builder->where($where);
        });

        $obj->when(!empty($orderBy), function ($builder) use ($orderBy) {
            foreach ($orderBy as [$column, $direction]) {
                $builder->orderBy($column, $direction);
            }
        });

        return $obj->paginate($perPage);
    }

    public function insert(array $data)
    {
        $this->boardConfig->insert($data);
    }
}
