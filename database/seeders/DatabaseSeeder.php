<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenusSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PermissionsSeeder::class,
            AccessSeeder::class,

            ProvinsiSeeder::class,
            KabupatenKotaSeeder::class,
            KecamatanSeeder::class,

            FAQSeeder::class,
            KategoriBeritaSeeder::class,
            SystemSettingSeeder::class,

            UnduhanSeeder::class,
            SlideshowSeeder::class,

            // == simulasi ==

            // RegisterSeeder::class,
            // PesertaSeeder::class,
            // FormulirMKSeeder::class,

            // == simulasi Pendaftar RPL dan penetapan kompetensi ==

            // ganti files/dummies.pdf dengan file yang ada di storage/app/public/files/

            // FormulirBerkasPendaftaranSeeder::class,
            // PembayaranSeeder::class,
            // FormulirMataKuliahCPMSeeder::class,
            // RiwayatOrganisasiSeeder::class,
            // RiwayatPekerjaanSeeder::class,
            // RiwayatPelatihanSeeder::class,
            // RiwayatPendidikanSeeder::class,
            // RiwayatPenghargaanSeeder::class,
        ]);
    }
}
