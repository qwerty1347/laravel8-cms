<?php

namespace Database\Seeders\MongoDB;

use App\Repositories\MongoDB\BoardConfigRepository;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Database\Seeder;

class BoardConfigSeeder extends Seeder
{
    protected BoardConfigRepository $boardConfigRepository;
    protected UTCDateTime $now;

    public function __construct()
    {
        $this->boardConfigRepository = new BoardConfigRepository();
        $this->now = new UTCDateTime(Carbon::now());
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'type' => 'notice',
                'user_id' => 1,
                'name' => '공지사항',
                'access_control' => [
                    'member' => true,
                    'guest' => true
                ],
                'created_at' => $this->now,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'type' => 'free',
                'user_id' => 1,
                'name' => '자유게시판',
                'access_control' => [
                    'member' => true,
                    'guest' => true
                ],
                'created_at' => $this->now,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'type' => 'qna',
                'user_id' => 1,
                'name' => 'Q&A',
                'access_control' => [
                    'member' => true,
                    'guest' => true
                ],
                'created_at' => $this->now,
                'updated_at' => null,
                'deleted_at' => null
            ]
        ];
        $this->boardConfigRepository->insert($data);
    }
}
