<?php

namespace Tests\Unit\Camaba;

use App\Helpers\Payment\Payment;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Master\Provinsi;
use App\Models\Payment\PaymentGateway;
use App\Models\Payment\PaymentGatewayOption;
use App\Models\Payment\PaymentProdi;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    use DatabaseTransactions;

    private $camaba;

    private $paymentGateway;

    private $paymentChannel;

    private $tahunAjaran;

    public function setupUser()
    {
        $admin = User::where('email', 'admin@arkatama.test')->first();
        $this->actingAs($admin);
        $prodi = ProgramStudi::first();

        $biaya = PaymentProdi::updateOrCreate([
            'prodi_id' => $prodi->id,
            'tahun_ajaran_id' => $this->tahunAjaran['id'],
        ], [
            'prodi_id' => $prodi->id,
            'tahun_ajaran_id' => $this->tahunAjaran['id'],
            'biaya_pendaftaran' => 100000,
            'is_free_ukt' => '0',
            'biaya_ukt' => 1000000,
        ]);

        $prodiPilihan = ProdiPilihan::updateOrCreate(['prodi_id' => $prodi->id], [
            'tahun_ajaran_id' => $this->tahunAjaran['id'],
            'prodi_id' => $prodi->id,
            'tanggal_mulai_pendaftaran' => now()->subDay(),
            'tanggal_selesai_pendaftaran' => now()->addDays(20),
            'tanggal_mulai_administrasi' => now()->subDay(),
            'tanggal_selesai_administrasi' => now()->addDays(18),
            'tanggal_pengumuman' => now()->addDays(30),
            'kuota_pendaftar' => 100,
            'is_active' => '1',
        ]);

        $kode_pendaftaran = 'KP'.rand(100000, 999999);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'camaba_test@arkatama.test',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make('12345'),
        ]);

        $user->assignRole('camaba');

        Register::create([
            'prodi_pilihan_id' => $prodiPilihan->id,
            'kode_pendaftaran' => $kode_pendaftaran,
            'nik' => '1234567890000987',
            'user_id' => (int) $user->id,
            'tahun_ajaran_id' => (int) TahunAjaran::getCurrent()['id'],
            'prodi_id' => $prodi->id,
            'asal_instansi' => 'Test Instansi',
            'provinsi_id' => (int) Provinsi::first()->id,
            'nama_lengkap' => 'Test User',
            'email' => 'camaba_test@arkatama.test',
            'nomor_telepon' => '6281234567890',
            'is_active' => '1',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $this->camaba = $user;

        $this->actingAs($this->camaba);
    }

    public function setupPaymentGateway()
    {
        $this->paymentGateway = PaymentGateway::updateOrCreate([
            'name' => 'Midtrans',
        ], [
            'name' => 'Midtrans',
            'helper' => 'MidtransGateway.php',
        ]);
        $this->paymentGateway->channels()->updateOrCreate([
            'kode' => 'bca_va',
        ], [
            'payment_type' => 'bank_transfer',
            'name' => 'BCA Virtual Account',
            'kode' => 'bca_va',
            'fee_customer_flat' => 4000,
            'fee_customer_percent' => null,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ]);

        $this->paymentChannel = $this->paymentGateway->channels->where('kode', 'bca_va')->first();

        PaymentGatewayOption::updateOrCreate([
            'tahun_ajaran_id' => $this->tahunAjaran['id'],
            'payment_gateway_id' => $this->paymentGateway->id,
        ], [
            'tahun_ajaran_id' => TahunAjaran::getCurrent()['id'],
            'payment_gateway_id' => $this->paymentGateway->id,
        ]);

    }

    public function setUp(): void
    {
        parent::setUp();
        $this->tahunAjaran = TahunAjaran::getCurrent();
        $this->setupPaymentGateway();
        $this->setupUser();
    }

    public function test_get_available_helpers_method_is_returning_array()
    {
        $payment = Payment::getAvailableHelpers();
        $this->assertIsArray($payment);
    }

    public function test_get_payment_channels_method_is_returning_collection()
    {
        $payment = Payment::getPaymentChannels();
        $this->assertIsObject($payment);
    }

    public function test_payment_can_forward_the_request_to_correct_helper()
    {
        $payment = Payment::getPaymentHelper($this->paymentChannel);
        $this->assertNotNull($payment);
    }

    public function test_make_payment_and_get_inquiry_is_returning_array()
    {
        $payment = Payment::makePayment($this->paymentChannel);
        $this->assertIsArray($payment);
        $pembayaran = $this->camaba->register->pembayaran;

        // open the payment url
        Http::get($payment['redirect_url']);

        $status = Payment::getPaymentStatus($pembayaran);
        $this->assertNotNull($status);
    }
}
