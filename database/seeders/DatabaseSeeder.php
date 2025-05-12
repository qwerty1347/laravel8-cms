<?php

namespace Database\Seeders;

use Database\Seeders\MongoDB\BoardConfigSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BoardConfigSeeder::class,
            BoardPostSeeder::class,
            BoardCommentSeeder::class
        ]);
    }
}
