<?php

namespace Database\Factories\Setting;

use App\Models\Setting\BackupSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class BackupHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'backup_schedule_id' => BackupSchedule::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['success', 'failed']),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
