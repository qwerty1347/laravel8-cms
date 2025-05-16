<?php

namespace App\Repositories\MongoDB;

use App\Models\MongoDB\BoardPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BoardPostRepository
{
    public const PER_PAGE = 10;
    protected BoardPost $boardPost;

    public function __construct()
    {
        $this->boardPost = new BoardPost();
    }

    /**
     * board_posts 다큐먼트 조회
     *
     * @param   array    $where    검색 조건
     * @param   array    $orderBy  [['created_at', 'desc'], [], ....]
     * @param   int      $perPage  페이지 별 리스트 개수 제한
     *
     * @return  Builder
     */
    public function getList(array $where, array $orderBy): Builder
    {
        return $this->boardPost::query()
            ->when(!empty($where), function ($builder) use ($where) {
                if ($where['config_name'] != 'all') {
                    $builder->where($where);
                }
            })
            ->when(!empty($orderBy), function ($builder) use ($orderBy) {
                foreach ($orderBy as [$column, $direction]) {
                    $builder->orderBy($column, $direction);
                }
            });
    }

    public function getPostList(array $where=[], array $orderBy=[]): Collection
    {
        return $this->getList($where, $orderBy)->get();
    }

    public function getPageList(array $where=[], array $orderBy=[]): LengthAwarePaginator
    {
        return $this->getList($where, $orderBy)->paginate(self::PER_PAGE);
    }
}
