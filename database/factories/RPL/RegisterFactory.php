<?php

namespace Database\Factories\RPL;

use App\Models\Akademik\TahunAjaran;
use App\Models\Master\Provinsi;
use App\Models\Rpl\ProdiPilihan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pilihanProdi = ProdiPilihan::with(['programStudi'])
            ->withCount(['register as register_count' => function ($q) {
                $q->whereHas('pembayaran');
            }])
            ->where('tahun_ajaran_id', optional(TahunAjaran::getCurrent())['id'])
            ->where('is_active', '1')
            ->whereDate('tanggal_mulai_pendaftaran', '<=', now())
            ->whereDate('tanggal_selesai_pendaftaran', '>', now())
            ->havingRaw('register_count < kuota_pendaftar')
            ->first();

        $provinsi = Provinsi::inRandomOrder()->first();

        $kode_pendaftaran = 'KP'.rand(100000, 999999);
        $no_telepon = '62'.substr($this->faker->numerify('###########'), 1);

        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make(12345),
            'email_verified_at' => now()->subHour(),
        ]);

        $user->assignRole('camaba');

        return [
            'prodi_pilihan_id' => $pilihanProdi->id,
            'kode_pendaftaran' => $kode_pendaftaran,
            'nik' => $this->faker->numerify('################'),
            'user_id' => (int) $user->id,
            'tahun_ajaran_id' => (int) TahunAjaran::getCurrent()['id'],
            'prodi_id' => $pilihanProdi->prodi_id,
            'asal_instansi' => 'Universitas pasundan',
            'provinsi_id' => $provinsi->id,
            'nama_lengkap' => $user->name,
            'email' => $user->email,
            'nomor_telepon' => $no_telepon,
            'is_active' => '1',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
