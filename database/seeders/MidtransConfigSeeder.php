<?php

namespace Database\Seeders;

use App\Models\Payment\MidtransConfig;
use Illuminate\Database\Seeder;

class MidtransConfigSeeder extends Seeder
{
    public function run()
    {
        $configMidtrans = [
            [
                'production_merchant_id' => 'G302877926',
                'production_client_key' => 'Mid-client-GzVLnEjIr758WOxL',
                'production_server_key' => 'Mid-server-mxBNWryBFWaWvbWA9SHHcP6p',
                'sandbox_merchant_id' => 'G302877926',
                'sandbox_client_key' => 'SB-Mid-client-WPKCxbA4TZTy2w4x',
                'sandbox_server_key' => 'SB-Mid-server-Yi8ZYoNBnWpXxSJYrMzcRHlS',
                'production_snap_url' => 'https://app.stg.midtrans.com/snap/snap.js',
                'sandbox_snap_url' => 'https://app.sandbox.midtrans.com/snap/snap.js',
                'is_production' => false,
                'is_sanitized' => true,
                'is_3ds' => true,
                'created_at' => now(),
            ],
        ];

        foreach ($configMidtrans as $config) {
            MidtransConfig::create($config);
        }
    }
}
