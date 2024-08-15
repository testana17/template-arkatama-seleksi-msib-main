<?php

namespace Database\Seeders;

use App\Models\Setting\BackupHistory;
use Illuminate\Database\Seeder;

class BackupHistorySeeder extends Seeder
{
    public function run(): void
    {
        BackupHistory::factory()->count(10)->create();
    }
}
