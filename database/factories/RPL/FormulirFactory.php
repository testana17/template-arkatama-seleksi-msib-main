<?php

namespace Database\Factories\RPL;

use App\Models\Rpl\Register;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormulirFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $register = Register::factory()->create();

        return [
            'register_id' => $register->id,
            'tahun_ajaran_id' => $register->tahun_ajaran_id,
            'nik' => $register->nik,
            'nama_lengkap' => $register->nama_lengkap,
            'email' => $register->email,
            'pilihan_prodi_id' => $register->prodi_id,
            'nomor_telepon' => $register->nomor_telepon,
            'provinsi_id' => $register->provinsi_id,
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
