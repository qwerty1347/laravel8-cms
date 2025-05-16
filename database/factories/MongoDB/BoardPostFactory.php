<?php

namespace Database\Factories\MongoDB;

use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'config_name' => null,
            'user_id' => 1,
            'title' => $this->faker->sentence,
            'contents' => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>',
            'plain_contents' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['active', 'inactive', 'hidden']),
            'created_at' => new UTCDateTime(Carbon::now()),
            'updated_at' => null,
            'deleted_at' => null,
        ];
    }
}
