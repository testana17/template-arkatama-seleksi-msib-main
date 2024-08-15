<?php

namespace Tests\Feature\Payment;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Payment\HistoriPembayaran;
use App\Models\Payment\LogPembayaran;
use App\Models\Payment\PaymentProdi;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Register;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\CRUDTestCase;

class HistoriPembayaranTest extends CRUDTestCase
{
    use DatabaseTransactions;

    private $adminUser;

    private $defaultPayload;

    private $route;

    private $table;

    public function setUp(): void
    {
        parent::setUp();
        $adminUser = User::where('email', 'admin@arkatama.test')->first();
        $this->adminUser = $adminUser;

        $biaya = PaymentProdi::create([
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
            'tahun_ajaran_id' => TahunAjaran::getCurrent()['id'],
            'is_free_ukt' => '0',
            'biaya_ukt' => 100000,
            'biaya_pendaftaran' => 2500000,
        ]);

        $register = Register::factory()->create([
            'prodi_id' => $biaya->prodi_id,
            'tahun_ajaran_id' => $biaya->tahun_ajaran_id,
        ]);

        $this->defaultPayload = [
            'register_id' => $register->id,
            'nominal_pembayaran' => $biaya->biaya_pendaftaran,
            'keterangan' => 'Pembayaran Biaya Pendaftaran',
            'bukti_pembayaran' => UploadedFile::fake()->image('bukti_pembayaran.jpg'),
        ];

        $this->route = 'histori-pembayaran.';
        $this->table = 'histori_pembayaran';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('histori-pembayaran');
        $this->setBaseModel(HistoriPembayaran::class);
    }

    public function test_must_authenticated_to_access_page(): void
    {
        $this->testAccess(route: $this->route.'index', method: 'get', user: null, status: 302);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $this->testAccess(route: $this->route.'index', method: 'get', user: $this->adminUser, status: 200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $this->testShowDatatable(route: $this->route.'index');
    }

    public function test_can_create_histori_pembayaran(): Model
    {
        $this->actingAs($this->adminUser);
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_access_page_show_histori_pembayaran(): void
    {
        $model = $this->test_can_create_histori_pembayaran();
        $this->testAccess(route: $this->route.'show', method: 'get', user: $this->adminUser, status: 302, params: ['histori_pembayaran' => $model->id]);
    }

    public function test_verifikasi_lunas_pembayaran(): void
    {
        $pembayaran = Pembayaran::factory()->create([
            'status' => 'belum lunas',
            'nominal' => 2400000,
            'sisa_tagihan' => 100000,
        ]);

        $payloadJson = json_encode($pembayaran);

        LogPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'payload' => $payloadJson,
        ]);

        $model = HistoriPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'status' => 'belum lunas',
            'nominal' => 2400000,
            'sisa_tagihan' => 100000,
            'payload' => $payloadJson,
            'tanggal_pembayaran' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        $this->actingAs($this->adminUser);
        $response = $this->putJson(route($this->route.'update', ['histori_pembayaran' => $pembayaran->id]), [
            'status' => 'lunas',
            'jenis_verifikasi' => 'manual',
            'sisa_tagihan' => 0,
            'nominal' => 2500000,
        ]);
        $response->assertStatus(200);
    }

    public function test_verifikasi_batal_pembayaran(): void
    {
        $pembayaran = Pembayaran::factory()->create([
            'status' => 'belum lunas',
            'nominal' => 2400000,
            'sisa_tagihan' => 100000,
        ]);

        $payloadJson = json_encode($pembayaran);

        LogPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'payload' => $payloadJson,
        ]);

        $model = HistoriPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'status' => 'belum lunas',
            'nominal' => 2400000,
            'sisa_tagihan' => 100000,
            'payload' => $payloadJson,
            'tanggal_pembayaran' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        $this->actingAs($this->adminUser);
        $response = $this->putJson(route($this->route.'update', ['histori_pembayaran' => $pembayaran->id]), [
            'status' => 'batal',
        ]);
        $response->assertStatus(200);
    }
}
