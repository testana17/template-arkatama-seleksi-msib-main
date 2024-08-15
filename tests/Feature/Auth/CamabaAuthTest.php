<?php

namespace Tests\Feature\Auth;

use App\Models\Akademik\TahunAjaran;
use App\Models\Master\Provinsi;
use App\Models\Rpl\ProdiPilihan;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Tests\CreatesApplication;
use Tests\TestCase;

class CamabaAuthTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    private $defaultPayload;

    private $validationRule;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

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
        $this->defaultPayload = [
            'prodis' => $pilihanProdi->programStudi->id,
            'nik' => '1234567890123445',
            'nama_lengkap' => 'John Doe',
            'instansi' => 'Universitas Indonesia',
            'provinsi' => $provinsi->id,
            'email' => 'johndoe@gmail.com',
            'nomor_telepon' => '081234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->validationRule = [
            'prodis' => ['required', 'string'],
            'nik' => ['required', 'string', 'digits:16', 'unique:registers'],
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:255'],
            'instansi' => ['required', 'string', 'max:255'],
            'provinsi' => ['required', 'max:10'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'nomor_telepon' => ['required', 'numeric', 'unique:registers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function test_camaba_can_access_register_page(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_camaba_register_with_invalid_data(): void
    {
        $invalidPayload = [
            'prodis' => 3,
            'nik' => '1234567890123445',
            'nama_lengkap' => 'John Doe',
            'instansi' => 'Universitas Indonesia',
            'provinsi' => null,
            'email' => 'johndoe@gmail.com',
            'nomor_telepon' => '081234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $validator = Validator::make($invalidPayload, $this->validationRule);
        $response = $this->post(route('register'), $invalidPayload);
        $response->assertSessionHasErrors($validator->errors()->keys());
        $this->assertFalse($validator->passes());
        $this->assertDatabaseMissing('registers', [
            'email' => $invalidPayload['email'],
            'nik' => $invalidPayload['nik'],
        ]);
    }

    public function test_camaba_register_with_valid_data(): void
    {
        $response = $this->post(route('register'), $this->defaultPayload);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertDatabaseHas('registers', [
            'email' => $this->defaultPayload['email'],
            'nik' => $this->defaultPayload['nik'],
        ]);
    }

    public function test_camaba_login_with_invalid_data(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'andong@gmail.com',
            'password' => 'passowrd',
        ]);
        $response->assertSessionHasErrors(['email']);
        $response->assertStatus(302);
    }

    public function test_camaba_login_with_valid_data(): void
    {
        $this->test_camaba_register_with_valid_data();
        $response = $this->post(route('login'), [
            'email' => $this->defaultPayload['email'],
            'password' => $this->defaultPayload['password'],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->user = User::where('email', $this->defaultPayload['email'])->first();
    }

    public function test_camaba_access_dashboard_before_email_verified(): void
    {
        $this->test_camaba_login_with_valid_data();
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_camaba_send_verification_email(): void
    {
        $this->test_camaba_login_with_valid_data();
        Notification::fake();

        $this->actingAs($this->user)->post(route('verification.resend'));

        Notification::assertSentTo(
            [$this->user],
            VerifyEmail::class
        );
    }

    public function test_camaba_verify_email(): void
    {
        $this->test_camaba_login_with_valid_data();
        $response = $this->actingAs($this->user)->get(route('verification.notice'));
        $response->assertStatus(200);
        $verificationUrl =
            URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $this->user->id, 'hash' => sha1($this->user->email)]
            );
        $response = $this->actingAs($this->user)->get($verificationUrl);
        $this->assertTrue($this->user->fresh()->hasVerifiedEmail());
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }
}
