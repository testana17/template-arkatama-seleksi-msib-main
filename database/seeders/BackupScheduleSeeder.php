<?php

namespace Database\Seeders;

use App\Models\Setting\BackupSchedule;
use Illuminate\Database\Seeder;

class BackupScheduleSeeder extends Seeder
{
    public function run(): void
    {
        BackupSchedule::factory()->count(5)->create();
    }
}
