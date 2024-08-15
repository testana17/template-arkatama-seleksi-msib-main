<?php

namespace Database\Seeders;

use App\Models\Master\KategoriBerita;
use Illuminate\Database\Seeder;

class KategoriBeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $kategoriBeritaData = [
            [
                'name' => 'Umum',
                'description' => 'Kategori berita yang berkaitan dengan hal-hal umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan',
                'description' => 'Kategori berita yang berkaitan dengan hal-hal pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Kategori berita yang berkaitan dengan hal-hal kesehatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teknologi',
                'description' => 'Kategori berita yang berkaitan dengan hal-hal teknologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Kategori berita yang berkaitan dengan hal-hal olahraga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($kategoriBeritaData as $data) {
            KategoriBerita::create($data);
        }
    }
}
