<?php

namespace Database\Seeders;

use App\Models\Setting\SystemSettingModel;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSettingModel::create([
            'name' => 'pajak',
            'value' => '11',
            'description' => 'PPN dalam angka 0-100 tanpa tanda persen. Misal: 11',
        ]);
    }
}
