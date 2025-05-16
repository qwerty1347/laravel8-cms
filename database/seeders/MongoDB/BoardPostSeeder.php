<?php

namespace Database\Seeders\MongoDB;

use App\Models\MongoDB\BoardConfig;
use App\Models\MongoDB\BoardPost;
use MongoDB\BSON\ObjectId;
use Illuminate\Database\Seeder;

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
                    'config_id' => new ObjectId($config->_id)
                ])
                ->create();
        }
    }
}
