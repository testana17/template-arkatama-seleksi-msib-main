<?php

namespace Database\Factories\Setting;

use Illuminate\Database\Eloquent\Factories\Factory;

class BackupScheduleFactory extends Factory
{
    public function definition(): array
    {

        return [
            'name' => $this->faker->sentence,
            'frequency' => $this->faker->randomElement(['daily', 'weekly', 'monthly']),
            'time' => $this->faker->time(),
        ];
    }
}
