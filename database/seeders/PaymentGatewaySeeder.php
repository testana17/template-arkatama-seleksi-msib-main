<?php

namespace Database\Seeders;

use App\Models\Payment\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::create([
            'name' => 'Midtrans',
            'helper' => 'MidtransGateway.php',
        ]);
    }
}
