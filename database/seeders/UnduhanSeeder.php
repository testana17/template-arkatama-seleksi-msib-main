<?php

namespace Database\Seeders;

use App\Models\Cms\Dokumen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UnduhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Storage::disk('public')->exists('dokumen')) {
            Storage::disk('public')->makeDirectory('dokumen');
        }

        if (! file_exists(storage_path('app/public/dokumen/template_pernyataan_kesediaan_dummy.pdf')) || ! file_exists(storage_path('app/public/dokumen/template_pernyataan_taat_dummy.pdf'))) {
            \Illuminate\Support\Facades\File::copy(public_path('docs/dummy/template_pernyataan_kesediaan.pdf'), storage_path('app/public/dokumen/template_pernyataan_kesediaan_dummy.pdf'));
            \Illuminate\Support\Facades\File::copy(public_path('docs/dummy/template_pernyataan_taat.pdf'), storage_path('app/public/dokumen/template_pernyataan_taat_dummy.pdf'));
        }

        $unduhan = [
            [
                'nama' => 'Template Pernyataan Kesediaan Mengikuti Pendidikan',
                'keterangan' => 'Template Pernyataan Kesediaan Mengikuti Pendidikan',
                'file' => 'dokumen/template_pernyataan_kesediaan_dummy.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'nama' => 'Template Pernyataan Taat Aturan Universitas',
                'keterangan' => 'Template Pernyataan Taat Aturan Universitas',
                'file' => 'dokumen/template_pernyataan_taat_dummy.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($unduhan as $item) {
            Dokumen::create($item);
        }
    }
}
