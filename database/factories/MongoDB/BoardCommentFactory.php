<?php

namespace Database\Factories\MongoDB;

use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => null,
            'post_id' => null,
            'user_id' => 1,
            'comment' => $this->faker->sentence,
            'created_at' => new UTCDateTime(Carbon::now()),
            'updated_at' => null,
            'deleted_at' => null
        ];
    }
}
