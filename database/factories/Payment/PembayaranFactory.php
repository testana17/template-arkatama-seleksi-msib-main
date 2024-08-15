<?php

namespace Database\Factories\Payment;

use App\Models\Payment\PaymentChannel;
use App\Models\Rpl\Formulir;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PembayaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $formulir = Formulir::factory()->create();

        return [
            'register_id' => $formulir->register->id,
            'payment_channel_id' => PaymentChannel::first()->id,
            'snap_token' => $this->faker->uuid(),
            'nominal' => 1000000,
            'sisa_tagihan' => 0,
            'status' => 'lunas',
        ];
    }
}
