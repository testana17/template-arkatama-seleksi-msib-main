<?php

namespace Database\Seeders;

use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Formulir;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::inRandomOrder()->first();
        Pembayaran::updateOrCreate([
            'register_id' => $formulir->register_id,
        ], [
            'id' => Str::uuid()->toString(),
            'trx_id' => Str::random(10),
            'virtual_account' => Str::random(16),
            'nominal' => 1000000,
            'sisa_tagihan' => 0,
            'tanggal_pembayaran' => now(),
            'tanggal_expired' => now()->addDays(1),
            'bukti_pembayaran' => 'files/dummies.pdf',
            'status' => 'lunas',
            'jenis_verifikasi' => 'otomatis',
            'keterangan' => 'Pembayaran lunas',
            'created_by' => $formulir->register->user_id,
            'updated_by' => $formulir->register->user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
