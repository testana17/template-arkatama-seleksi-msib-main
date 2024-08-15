<?php

namespace Database\Seeders;

use App\Models\Master\KabupatenKota;
use Illuminate\Database\Seeder;
use SplFileObject;

class KabupatenKotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = new SplFileObject(public_path('docs/csv/kabupaten_kota.csv'));
        $file->setFlags(SplFileObject::READ_CSV);

        $kabupatenCollection = collect();

        foreach ($file as $index => $row) {
            if ($index > 0) {
                if ($row[0] != null && $row[1] != null) {
                    $semua_provinsi = \App\Models\Master\Provinsi::all();
                    $kode_provinsi = explode('.', $row[0])[0];
                    $kode_kabupaten = explode('.', $row[0])[1];
                    foreach ($semua_provinsi as $provinsi) {
                        if ($provinsi->kode == $kode_provinsi) {
                            $kabupatenCollection->push([
                                'provinsi_id' => (int) $provinsi->id,
                                'kode' => (int) $kode_provinsi.'.'.(int) $kode_kabupaten,
                                'nama' => $row[1],
                            ]);
                        }
                    }
                }
            }
        }

        $kabupatenChunks = $kabupatenCollection->chunk(100);

        foreach ($kabupatenChunks as $chunk) {
            $kabupatenInsert = $chunk->map(function ($kabupaten) {
                return [
                    'provinsi_id' => $kabupaten['provinsi_id'],
                    'kode' => $kabupaten['kode'],
                    'nama' => $kabupaten['nama'],
                ];
            });

            KabupatenKota::insert($kabupatenInsert->toArray());
        }
    }
}
