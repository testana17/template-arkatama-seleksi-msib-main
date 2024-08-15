<?php

namespace Tests\Feature;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Timeline;
use App\Models\Rpl\ProdiPilihan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    protected $register_payload;

    protected $randomProdiPilihan;

    public static function setUpBeforeClass(): void {}

    public function setUp(): void
    {
        parent::setUp();
        $this->randomProdiPilihan = ProdiPilihan::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->first();
        $this->register_payload = [
            'nama_lengkap' => 'Ahmad Basofi Riswanto',
            'email' => 'basofi.cucokmeong12@gmail.com',
            'password' => 'Allahuakbar21',
            'password_confirmation' => 'Allahuakbar21',
            'nomor_telepon' => '088217069611',
            'prodis' => $this->randomProdiPilihan->prodi_id,
            'instansi' => 'Universitas PGRI Kanjuruhan Malang',
            'nik' => '3507112001030003',
            'provinsi' => 25,
        ];
    }

    public function test_can_access_register_page()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    public function test_user_got_message_when_the_registration_schedule_is_closed()
    {
        Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->delete();
        ProdiPilihan::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->delete();
        $this->get(route('register'))->assertSee('Mohon maaf, pendaftaran telah ditutup. Silahkan hubungi panitia PMB untuk informasi lebih lanjut.');
    }

    public function test_user_cant_register_on_prodi_that_doesnt_open_registration_schedule()
    {
        ProdiPilihan::limit(2)->delete();
        $prodiThatHasSchedule = ProdiPilihan::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->get(['prodi_id'])->pluck('prodi_id');
        $this->request(route('register'), 'POST', payload: [
            'nama_lengkap' => 'Ahmad Basofi Riswanto',
            'email' => 'basofi.cucokmeong12@gmail.com',
            'password' => 'Allahuakbar21',
            'password_confirmation' => 'Allahuakbar21',
            'nomor_telepon' => '088217069611',
            'prodis' => ProgramStudi::whereNotIn('id', $prodiThatHasSchedule)->first()->id,
            'instansi' => 'Universitas PGRI Kanjuruhan Malang',
            'nik' => '3507112001030003',
            'provinsi' => 25,
        ])->assertBadRequest();
    }

    public function test_user_cant_register_on_prodi_that_has_closed_registration_schedule()
    {
        ProdiPilihan::where('id', $this->randomProdiPilihan->id)->update([
            'tanggal_mulai_pendaftaran' => now()->subDays(2),
            'tanggal_selesai_pendaftaran' => now()->subDays(1),
        ]);
        $this->request(route('register'), 'POST', payload: $this->register_payload)->assertBadRequest();
    }

    public function test_user_can_register_on_prodi_that_has_open_registration_schedule()
    {
        $this->request(route('register'), 'POST', payload: $this->register_payload)
            ->assertSuccessful();
        $this->assertDatabaseHas('users', collect($this->register_payload)->only('email')->toArray());
        $this->assertDatabaseHas('registers', [
            'tahun_ajaran_id' => TahunAjaran::getCurrent()['id'],
            'prodi_id' => $this->randomProdiPilihan->prodi_id,
            'asal_instansi' => $this->register_payload['instansi'],
            'provinsi_id' => $this->register_payload['provinsi'],
            ...collect($this->register_payload)->only(['nama_lengkap', 'email', 'nik'])->toArray(),
        ]);
    }

    public function test_user_cannot_access_dashboard_if_email_not_verified(): void
    {
        $this->test_user_can_register_on_prodi_that_has_open_registration_schedule();
        $this->PostJson(route('login'), [
            'email' => $this->register_payload['email'],
            'password' => $this->register_payload['password'],
        ]);
        $this->assertAuthenticated();
        $this->get(route('dashboard'))->assertRedirect(route('verification.notice'));
    }
}
