<?php

namespace Database\Seeders;

use App\Models\Master\Kecamatan;
use Illuminate\Database\Seeder;
use SplFileObject;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = new SplFileObject(public_path('docs/csv/kecamatan.csv'));
        $file->setFlags(SplFileObject::READ_CSV);

        $kecamatanCollection = collect();

        foreach ($file as $index => $row) {
            if ($index > 0 && $row[0] !== null && $row[1] !== null) {
                $kode_provinsi = explode('.', $row[0])[0];
                $kode_kabupaten = explode('.', $row[0])[1];
                $kode_kecamatan = explode('.', $row[0])[2];

                $provinsi = \App\Models\Master\Provinsi::where('kode', $kode_provinsi)->first();
                $kode_kabupaten_prov = (int) $kode_provinsi.'.'.(int) $kode_kabupaten;
                $kabupaten = \App\Models\Master\KabupatenKota::where('kode', $kode_kabupaten_prov)->where('provinsi_id', $provinsi->id)->first();
                if ($kabupaten) {
                    $kode_kabupaten = $kabupaten->id;
                    $kode_kabupaten_kota_prov = $kode_kabupaten_prov.'.'.(int) $kode_kecamatan;
                    $kecamatanCollection->push([
                        'kabupaten_kota_id' => (int) $kode_kabupaten,
                        'kode' => $kode_kabupaten_kota_prov,
                        'nama' => $row[1],
                    ]);
                }
            }
        }

        $kecamatanChunks = $kecamatanCollection->chunk(1000);

        foreach ($kecamatanChunks as $chunk) {
            $kecamatanInsert = $chunk->map(function ($kabupaten) {
                return [
                    'kabupaten_kota_id' => $kabupaten['kabupaten_kota_id'],
                    'kode' => $kabupaten['kode'],
                    'nama' => $kabupaten['nama'],
                ];
            });

            Kecamatan::insert($kecamatanInsert->toArray());
        }
    }
}
