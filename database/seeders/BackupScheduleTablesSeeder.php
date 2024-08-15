<?php

namespace Database\Seeders;

use App\Models\Setting\BackupScheduleTables;
use Illuminate\Database\Seeder;

class BackupScheduleTablesSeeder extends Seeder
{
    public function run(): void
    {
        BackupScheduleTables::factory()->count(20)->create();
    }
}
