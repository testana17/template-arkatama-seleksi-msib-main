<?php

namespace Database\Seeders;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Payment\PaymentGateway;
use App\Models\Payment\PaymentGatewayOption;
use App\Models\Payment\PaymentProdi;
use Illuminate\Database\Seeder;

class PaymentProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaranId = TahunAjaran::getCurrent()['id'];
        $prodiIds = ProgramStudi::pluck('id')->toArray();

        $biayaPendaftaran = [1000000, 2000000, 3000000, 4000000, 5000000];
        $biayaUkt = [400000, 800000, 1200000, 1600000, 2000000];

        foreach ($prodiIds as $prodiId) {
            PaymentProdi::create([
                'prodi_id' => $prodiId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'biaya_pendaftaran' => $biayaPendaftaran[array_rand($biayaPendaftaran)],
                'is_free_ukt' => '0',
                'biaya_ukt' => $biayaUkt[array_rand($biayaUkt)],
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ]);
        }

        PaymentGatewayOption::create([
            'tahun_ajaran_id' => $tahunAjaranId,
            'payment_gateway_id' => PaymentGateway::first()->id,
            'is_active' => '1',
            'created_at' => now(),
        ]);
    }
}
