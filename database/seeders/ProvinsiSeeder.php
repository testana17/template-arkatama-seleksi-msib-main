<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SplFileObject;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = new SplFileObject(public_path('docs/csv/provinsi.csv'));
        $file->setFlags(SplFileObject::READ_CSV);
        foreach ($file as $index => $row) {
            if ($index > 0) {
                if ($row[0] != null && $row[1] != null) {
                    \App\Models\Master\Provinsi::create([
                        'kode' => (int) $row[0],
                        'nama' => $row[1],
                    ]);
                }
            }
        }

        // $file = new SplFileObject(public_path('docs/csv/provinsi.csv'));
        // $file->setFlags(SplFileObject::READ_CSV);

        // $batchSize = 50;
        // $batchData = collect();

        // foreach ($file as $index => $row) {
        //     if ($index > 0 && $row[0] !== null && $row[1] !== null) {
        //         $batchData->push([
        //             'kode' => (int)$row[0],
        //             'nama' => $row[1],
        //         ]);

        //         if ($batchData->count() === $batchSize) {
        //             // Insert batch data to database
        //             \App\Models\Provinsi::insert($batchData->toArray());

        //             // Clear batch data
        //             $batchData = collect();
        //         }
        //     }
        // }

        // // Insert remaining data if any
        // if ($batchData->isNotEmpty()) {
        //     \App\Models\Provinsi::insert($batchData->toArray());
        // }
    }
}
