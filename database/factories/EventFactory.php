<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EventFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'scheduled_at' => Carbon::now()->addMonths(2),
            'location' => $this->faker->city,
            'max_attendees' => $this->faker->numberBetween(10, 200)
        ];
    }
}
