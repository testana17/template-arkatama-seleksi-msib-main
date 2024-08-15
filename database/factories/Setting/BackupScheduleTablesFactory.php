<?php

namespace Database\Factories\Setting;

use App\Models\Setting\BackupSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class BackupScheduleTablesFactory extends Factory
{
    public function definition(): array
    {
        return [
            //random backup_schedule_id
            'backup_schedule_id' => BackupSchedule::inRandomOrder()->first()->id,
            'table_name' => DB::table('information_schema.tables')
                ->select('table_name')
                ->where('table_schema', config('database.connections.mysql.database'))
                ->inRandomOrder()
                ->first()->TABLE_NAME,
        ];
    }
}
