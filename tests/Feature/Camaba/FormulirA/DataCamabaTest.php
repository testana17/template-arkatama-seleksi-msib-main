<?php

namespace Tests\Feature\Camaba\FormulirA;

use App\Models\Payment\Pembayaran;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DataCamabaTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    protected $base_route;

    protected $base_user;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->base_route = 'formulir-f02.data-camaba';
        $pembayaran = Pembayaran::factory()->create();

        $this->base_route = 'formulir-f02.data-camaba';
        $this->base_user = $pembayaran->register->user;
        $this->admin = User::where('email', 'admin@arkatama.test')->first();

        $this->removeAllUploadedRegistrationFile();
        $this->makeRegistrationSchedulePassed();
    }

    public function test_cant_acces_when_not_in_registration_schedule(): void
    {
        $registration = $this->base_user->register;
        ProdiPilihan::where([
            'tahun_ajaran_id' => $registration->tahun_ajaran_id,
            'prodi_id' => $registration->prodi_id,
        ])->update([
            'tanggal_mulai_pendaftaran' => now()->addDay(-50),
            'tanggal_selesai_pendaftaran' => now()->addDays(-30),
            'tanggal_mulai_administrasi' => now()->addDay(-30),
            'tanggal_selesai_administrasi' => now()->addDays(-10),
        ]);

        $this->actingAs($this->base_user)->get(route('formulir-f02.data-camaba.index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_cant_access_when_registration_file_not_fullfilled(): void
    {
        $this->actingAs($this->base_user)->get(route('formulir-f02.data-camaba.index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_can_access_when_in_registration_schedule_and_the_registration_file_fullfilled(): void
    {
        $this->giveAccess();
        $this->actingAs($this->base_user)->get(route('formulir-f02.data-camaba.index'))
            ->assertStatus(200);
    }

    public function test_cannot_change_data_camaba_when_the_nama_lengkap_field_is_empty(): void
    {
        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('nama_lengkap');
        });
    }

    public function test_cannot_change_data_camaba_when_the_tempat_lahir_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('tempat_lahir');
        });
    }

    public function test_cannot_change_data_camaba_when_the_status_pernikahan_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('status_pernikahan');
        });
    }

    public function test_cannot_change_data_camaba_when_the_kebangsaan_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'alamat' => 'Jl. Jalan',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('kebangsaan');
        });
    }

    public function test_cannot_change_data_camaba_when_the_alamat_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('alamat');
        });
    }

    public function test_cannot_change_data_camaba_when_the_provinsi_id_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('provinsi_id');
        });
    }

    public function test_cannot_change_data_camaba_when_the_kabupaten_kota_id_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('kabupaten_kota_id');
        });
    }

    public function test_cannot_change_data_camaba_when_the_pendidikan_terakhir_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'kabupaten_kota_id' => 3201,
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('pendidikan_terakhir');
        });
    }

    public function test_cannot_change_data_camaba_when_the_nama_instansi_pendidikan_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('nama_instansi_pendidikan');
        });
    }

    public function test_cannot_change_data_camaba_when_the_jurusan_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('jurusan');
        });
    }

    public function test_cannot_change_data_camaba_when_the_tahun_lulus_field_is_empty(): void
    {

        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan',
            'provinsi_id' => 32,
            'jurusan' => 'IPA',
            'kabupaten_kota_id' => 3201,
            'pendidikan_terakhir' => 'SMA',
        ], function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('tahun_lulus');
        });
    }

    public function test_can_change_data_camaba_when_all_required_field_is_filled(): void
    {
        $this->giveAccess();
        $this->request(route('formulir-f02.data-camaba.store'), 'POST', $this->base_user, [
            'nama_lengkap' => 'Ahmad Basofi RSWT',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Hareudang, no.20 Bandung',
            'jenis_kelamin' => 'L',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => 100,
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'jurusan' => 'IPA',
            'tahun_lulus' => 2017,
        ], function ($response) {
            $response->assertStatus(200);
            DB::rollBack();
        });
    }

    private function makeRegistrationSchedulePassed()
    {
        $registration = $this->base_user->register;
        ProdiPilihan::where([
            'tahun_ajaran_id' => $registration->tahun_ajaran_id,
            'prodi_id' => $registration->prodi_id,
        ])->update([
            'tanggal_mulai_pendaftaran' => now()->addDay(-50),
            'tanggal_selesai_pendaftaran' => now()->addDays(-30),
            'tanggal_mulai_administrasi' => now()->addDay(-30),
            'tanggal_selesai_administrasi' => now()->addDays(-10),
        ]);
    }

    private function removeAllUploadedRegistrationFile()
    {
        FormulirBerkasPersyaratan::where('formulir_id', $this->base_user->formulir->id)->delete();
    }

    private function giveAccess()
    {
        $registration = $this->base_user->register;
        $formulir = $this->base_user->formulir;

        $this->actingAs($this->admin);

        ProdiPilihan::where([
            'tahun_ajaran_id' => $registration->tahun_ajaran_id,
            'prodi_id' => $registration->prodi_id,
        ])->update([
            'tanggal_mulai_pendaftaran' => now()->addDay(-30),
            'tanggal_selesai_pendaftaran' => now()->addDays(-10),
            'tanggal_mulai_administrasi' => now()->addDay(-10),
            'tanggal_selesai_administrasi' => now()->addDays(30),
        ]);

        SyaratPendaftaran::where('prodi_id', $registration->prodi_id)->each(function ($syarat) use ($formulir) {
            FormulirBerkasPersyaratan::create([
                'formulir_id' => $formulir->id,
                'keterangan' => 'ashflsdflaskdm',
                'persyaratan_id' => $syarat->id,
                'file_pendukung' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
            ]);
        });
    }
}
