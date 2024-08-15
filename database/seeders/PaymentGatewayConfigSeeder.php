<?php

namespace Database\Seeders;

use App\Models\Payment\PaymentGateway;
use App\Models\Payment\PaymentGatewayConfig;
use Illuminate\Database\Seeder;

class PaymentGatewayConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $midtrans = [
            'PRODUCTION_MERCHANT_ID' => 'G302877926',
            'PRODUCTION_CLIENT_KEY' => 'Mid-client-GzVLnEjIr758WOxL',
            'PRODUCTION_SERVER_KEY' => 'Mid-server-mxBNWryBFWaWvbWA9SHHcP6p',
            'SANDBOX_MERCHANT_ID' => 'G302877926',
            'SANDBOX_CLIENT_KEY' => 'SB-Mid-client-WPKCxbA4TZTy2w4x',
            'SANDBOX_SERVER_KEY' => 'SB-Mid-server-Yi8ZYoNBnWpXxSJYrMzcRHlS',
            'PRODUCTION_SNAP_URL' => 'https://app.stg.midtrans.com/snap/snap.js',
            'SANDBOX_SNAP_URL' => 'https://app.sandbox.midtrans.com/snap/snap.js',
            'IS_PRODUCTION' => false,
            'IS_SANITIZED' => true,
            'IS_3DS' => true,
        ];

        $paymentGateway = PaymentGateway::where('name', 'Midtrans')->first();
        foreach ($midtrans as $key => $value) {
            if ($paymentGateway) {
                PaymentGatewayConfig::create([
                    'payment_gateway_id' => $paymentGateway->id,
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }
    }
}
