<?php

namespace Database\Seeders\MongoDB;

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
        BoardPost::factory()->count(10)->create();
    }
}
