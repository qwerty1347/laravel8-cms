<?php

namespace Database\Seeders\MongoDB;

use App\Models\MongoDB\BoardConfig;
use Illuminate\Database\Seeder;
use App\Models\MongoDB\BoardPost;

class BoardPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configs = BoardConfig::all();

        foreach ($configs as $config) {
            BoardPost::factory()
                ->count(50)
                ->state([
                    'config_id' => $config->_id
                ])
                ->create();
        }
    }
}
