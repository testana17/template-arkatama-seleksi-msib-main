<?php

namespace Database\Seeders;

use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\SyaratPendaftaran;
use Illuminate\Database\Seeder;

class FormulirBerkasPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::first();
        $persyaratan = SyaratPendaftaran::where('created_by', $formulir->register->user_id)->first();

        $data = [
            [
                'formulir_id' => $formulir->id,
                'persyaratan_id' => $persyaratan->id,
                'file_pendukung' => 'files/dummies.pdf',
                'is_valid' => '1',
                'keterangan' => 'Keterangan Berkas KTP',
                'created_by' => $formulir->register->user_id,
                'updated_by' => $formulir->register->user_id,
            ],
            [
                'formulir_id' => $formulir->id,
                'persyaratan_id' => $persyaratan->id,
                'file_pendukung' => 'files/dummies.pdf',
                'is_valid' => '1',
                'keterangan' => 'Keterangan Berkas Ijazah',
                'created_by' => $formulir->register->user_id,
                'updated_by' => $formulir->register->user_id,
            ],
            [
                'formulir_id' => $formulir->id,
                'persyaratan_id' => $persyaratan->id,
                'file_pendukung' => 'files/dummies.pdf',
                'is_valid' => '1',
                'keterangan' => 'Keterangan Berkas SKCK',
                'created_by' => $formulir->register->user_id,
                'updated_by' => $formulir->register->user_id,
            ],
            [
                'formulir_id' => $formulir->id,
                'persyaratan_id' => $persyaratan->id,
                'file_pendukung' => 'files/dummies.pdf',
                'is_valid' => '1',
                'keterangan' => 'Keterangan Berkas Surat Keterangan Sehat',
                'created_by' => $formulir->register->user_id,
                'updated_by' => $formulir->register->user_id,
            ],
            [
                'formulir_id' => $formulir->id,
                'persyaratan_id' => $persyaratan->id,
                'file_pendukung' => 'files/dummies.pdf',
                'is_valid' => '1',
                'keterangan' => 'Keterangan Berkas Surat Keterangan Bebas Narkoba',
                'created_by' => $formulir->register->user_id,
                'updated_by' => $formulir->register->user_id,
            ],
        ];

        foreach ($data as $key => $value) {
            $berkas = new FormulirBerkasPersyaratan;
            $berkas->seed = true;
            $berkas->fill($value);
            $berkas->save();
        }
    }
}
