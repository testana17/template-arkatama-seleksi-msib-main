<?php

namespace Database\Seeders;

use App\Models\FileManager;
use Illuminate\Database\Seeder;

class FileManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FileManager::factory()->count(5)->create();
    }
}
