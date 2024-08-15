<?php

namespace Tests\Feature\Camaba\Formulir07;

use App\Models\Camaba\FormulirF07\Pekerjaan;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\CPM;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\CreatesApplication;
use Tests\TestCase;

class PekerjaanTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $user;

    protected $payload;

    protected $table = 'riwayat_pekerjaan';

    protected $model = Pekerjaan::class;

    protected $route = 'formulir-f07.pekerjaan.';

    protected $routeParam = 'pekerjaan';

    public function setUp(): void
    {
        parent::setUp();
        $pembayaran = Pembayaran::factory()->create();
        $formulir = $pembayaran->register->formulir;

        $formulir->update([
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan Baru',
            'kabupaten_kota_id' => 1,
            'nomor_telepon' => '08123456789',
            'pendidikan_terakhir' => 'SMA/SMK',
            'nama_instansi_pendidikan' => 'SMAN 1 Jakarta',
            'jurusan' => 'IPA',
            'tahun_lulus' => '2018',
        ]);

        $this->user = User::where('email', $formulir->register->email)->first();
        $this->actingAs($this->user);

        SyaratPendaftaran::where('prodi_id', $formulir->pilihan_prodi_id)->each(function ($syarat) use ($formulir) {
            FormulirBerkasPersyaratan::create([
                'formulir_id' => $formulir->id,
                'keterangan' => 'Keretangan',
                'persyaratan_id' => $syarat->id,
                'file_pendukung' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
            ]);
        });
        $listMatakuliahFormulir = Matakuliah::where('prodi_id', $formulir->pilihan_prodi_id)->get();
        foreach ($listMatakuliahFormulir as $matakuliahFormulir) {
            CPM::where('matkul_id', $matakuliahFormulir->id)->each(function ($cpm) use ($formulir) {
                FormulirMatakuliahCPM::create([
                    'formulir_id' => $formulir->id,
                    'matkul_id' => $cpm->matkul_id,
                    'matkul_cpm_id' => $cpm->id,
                    'tingkat_penguasaan' => 'Cukup',
                    'keterangan' => 'Keterangan',
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            });
        }
        Auth::logout();

        $this->payload = [
            'nama_perusahaan' => 'PT. Arkatama',
            'jabatan' => 'Staff',
            'tanggal_masuk' => '2021-01-01',
            'tanggal_keluar' => '2021-01-02',
            'alamat_perusahaan' => 'Jl. Jalan Baru',
            'uraian_pekerjaan' => 'Mengerjakan sesuatu',
            'bukti_pekerjaan' => UploadedFile::fake()->create('file.png', 1000, 'image/png'),
        ];
    }

    public function test_must_authenticated_to_access_page(): void
    {
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(302);
    }

    public function test_must_in_schedule_to_access_page(): void
    {
        $formulir = Formulir::factory()->create();
        $formulir->register->prodiPilihan->update([
            'tanggal_mulai_pendaftaran' => now()->subDays(2),
            'tanggal_selesai_administrasi' => now()->subDay(),
        ]);
        $user = User::where('email', $formulir->email)->first();
        $response = $this->actingAs($user)
            ->get(route($this->route.'index'));
        $response->assertRedirectContains(route('dashboard'));
        $response->assertStatus(302);
        $formulir->register->prodiPilihan->update([
            'tanggal_mulai_pendaftaran' => now()->subDays(2),
            'tanggal_selesai_administrasi' => now()->addDay(),
        ]);
    }

    public function test_must_already_payment_is_completed_to_access_page(): void
    {
        $formulir = Formulir::factory()->create();
        $user = User::where('email', $formulir->register->email)->first();
        $response = $this->actingAs($user)
            ->get(route($this->route.'index'));
        $response->assertRedirectContains(route('dashboard'));
        $response->assertStatus(302);
    }

    public function test_cant_access_when_registration_file_not_fullfilled(): void
    {
        FormulirMatakuliahCPM::where('formulir_id', $this->user->formulir->id)->delete();
        $this->actingAs($this->user)->get(route($this->route.'index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_the_index_page_returns_successful_response_when_user_is_authenticated(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'index'));
        $response->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_entry_histories_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'histori'), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);
        $response->assertJsonStructure(['data']);
    }

    public function test_can_create_pekerjaan_with_valid_payload(): void
    {
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertCreated();
        $model = new $this->model($this->payload);
        $this->assertDatabaseHas($this->table, $model->toArray());
    }

    public function test_can_update_pekerjaan_with_valid_payload(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $updatePayload = array_merge($this->payload, [
            'nama_perusahaan' => 'PT Arkatama Multi Solusindo',
            'jabatan' => 'Manager',
            'uraian_pekerjaan' => 'Mengerjakan sesuatu yang lain',
        ]);
        $model = $this->model::first();
        $updateModel = new $this->model($updatePayload);
        $this->actingAs($this->user)
            ->putJson(route(
                $this->route.'update',
                [$this->routeParam => $model['id']]
            ), $updatePayload)
            ->assertOk();
        $this->assertDatabaseHas($this->table, $updateModel->toArray());
        $this->assertDatabaseMissing($this->table, $model->toArray());
    }

    public function test_can_delete_pekerjaan(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->deleteJson(route(
                $this->route.'destroy',
                [$this->routeParam => $model['id']]
            ))
            ->assertOk();

        $this->assertDatabaseMissing($this->table, $model->toArray());
    }

    public function test_can_restore_pekerjaan(): void
    {
        $this->test_can_delete_pekerjaan();
        $model = $this->model::onlyTrashed()->first();

        $this->actingAs($this->user)
            ->putJson(route(
                $this->route.'restore',
                [$this->routeParam => $model['id']]
            ))
            ->assertOk();
    }

    public function test_validation_create_pekerjaan_with_nama_perusahaan_required(): void
    {
        $this->payload['nama_perusahaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_perusahaan');
    }

    public function test_validation_create_pekerjaan_with_nama_perusahaan_min(): void
    {
        $this->payload['nama_perusahaan'] = 'PT';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_perusahaan');
    }

    public function test_validation_create_pekerjaan_with_nama_perusahaan_max(): void
    {
        $this->payload['nama_perusahaan'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_perusahaan');
    }

    public function test_validation_create_pekerjaan_with_jabatan_required(): void
    {
        $this->payload['jabatan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_create_pekerjaan_with_jabatan_min(): void
    {
        $this->payload['jabatan'] = 'St';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_create_pekerjaan_with_jabatan_max(): void
    {
        $this->payload['jabatan'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_create_pekerjaan_with_tanggal_masuk_required(): void
    {
        $this->payload['tanggal_masuk'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_masuk');
    }

    public function test_validation_create_pekerjaan_with_tanggal_masuk_date(): void
    {
        $this->payload['tanggal_masuk'] = 'dua ribu dua satu';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_masuk');
    }

    public function test_validation_create_pekerjaan_with_tanggal_masuk_before_or_equal(): void
    {
        $this->payload['tanggal_masuk'] = '2021-01-02';
        $this->payload['tanggal_keluar'] = '2021-01-01';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_masuk');
    }

    public function test_validation_create_pekerjaan_with_tanggal_keluar_required(): void
    {
        $this->payload['tanggal_keluar'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_keluar');
    }

    public function test_validation_create_pekerjaan_with_tanggal_keluar_date(): void
    {
        $this->payload['tanggal_keluar'] = 'dua ribu dua satu';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_keluar');
    }

    public function test_validation_create_pekerjaan_with_tanggal_keluar_after_or_equal(): void
    {
        $this->payload['tanggal_keluar'] = '2021-01-01';
        $this->payload['tanggal_masuk'] = '2021-01-02';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_keluar');
    }

    public function test_validation_create_pekerjaan_with_alamat_perusahaan_required(): void
    {
        $this->payload['alamat_perusahaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('alamat_perusahaan');
    }

    public function test_validation_create_pekerjaan_with_alamat_perusahaan_min(): void
    {
        $this->payload['alamat_perusahaan'] = 'Jl';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('alamat_perusahaan');
    }

    public function test_validation_create_pekerjaan_with_alamat_perusahaan_max(): void
    {
        $this->payload['alamat_perusahaan'] = str_repeat('a', 201);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('alamat_perusahaan');
    }

    public function test_validation_create_pekerjaan_with_uraian_pekerjaan_required(): void
    {
        $this->payload['uraian_pekerjaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('uraian_pekerjaan');
    }

    public function test_validation_create_pekerjaan_with_uraian_pekerjaan_min(): void
    {
        $this->payload['uraian_pekerjaan'] = 'Ur';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('uraian_pekerjaan');
    }

    public function test_validation_create_pekerjaan_with_uraian_pekerjaan_max(): void
    {
        $this->payload['uraian_pekerjaan'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('uraian_pekerjaan');
    }

    public function test_validation_create_pekerjaan_with_bukti_pekerjaan_required(): void
    {
        $this->payload['bukti_pekerjaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }

    public function test_validation_create_pekerjaan_with_bukti_pekerjaan_file(): void
    {
        $this->payload['bukti_pekerjaan'] = UploadedFile::fake()->create('file.pdfs', 1000, 'text/plain');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }

    public function test_validation_create_pekerjaan_with_bukti_pekerjaan_mimes(): void
    {
        $this->payload['bukti_pekerjaan'] = UploadedFile::fake()->create('file.exe', 1000, 'application/exe');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }

    public function test_validation_create_pekerjaan_with_bukti_pekerjaan_max(): void
    {
        $this->payload['bukti_pekerjaan'] = UploadedFile::fake()->create('file.pdf', 3000, 'application/pdf');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }

    public function test_validation_update_pekerjaan_with_nama_perusahaan_required(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['nama_perusahaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_perusahaan');
    }

    public function test_validation_update_pekerjaan_with_nama_perusahaan_min(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['nama_perusahaan'] = 'PT';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_perusahaan');
    }

    public function test_validation_update_pekerjaan_with_nama_perusahaan_max(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['nama_perusahaan'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_perusahaan');
    }

    public function test_validation_update_pekerjaan_with_jabatan_required(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['jabatan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_update_pekerjaan_with_jabatan_min(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['jabatan'] = 'St';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_update_pekerjaan_with_jabatan_max(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['jabatan'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_update_pekerjaan_with_tanggal_masuk_required(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['tanggal_masuk'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_masuk');
    }

    public function test_validation_update_pekerjaan_with_tanggal_masuk_date(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['tanggal_masuk'] = 'dua ribu dua puluh';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_masuk');
    }

    public function test_validation_update_pekerjaan_with_tanggal_masuk_before_or_equal(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['tanggal_masuk'] = '2021-01-02';
        $this->payload['tanggal_keluar'] = '2021-01-01';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_masuk');
    }

    public function test_validation_update_pekerjaan_with_tanggal_keluar_required(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['tanggal_keluar'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_keluar');
    }

    public function test_validation_update_pekerjaan_with_tanggal_keluar_date(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['tanggal_keluar'] = 'dua ribu dua puluh';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_keluar');
    }

    public function test_validation_update_pekerjaan_with_tanggal_keluar_after_or_equal(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['tanggal_keluar'] = '2021-01-01';
        $this->payload['tanggal_masuk'] = '2021-01-02';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_keluar');
    }

    public function test_validation_update_pekerjaan_with_alamat_perusahaan_required(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['alamat_perusahaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('alamat_perusahaan');
    }

    public function test_validation_update_pekerjaan_with_alamat_perusahaan_min(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['alamat_perusahaan'] = 'Jl';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('alamat_perusahaan');
    }

    public function test_validation_update_pekerjaan_with_alamat_perusahaan_max(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['alamat_perusahaan'] = str_repeat('a', 201);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('alamat_perusahaan');
    }

    public function test_validation_update_pekerjaan_with_uraian_pekerjaan_required(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['uraian_pekerjaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('uraian_pekerjaan');
    }

    public function test_validation_update_pekerjaan_with_uraian_pekerjaan_min(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['uraian_pekerjaan'] = 'Ur';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('uraian_pekerjaan');
    }

    public function test_validation_update_pekerjaan_with_uraian_pekerjaan_max(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['uraian_pekerjaan'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('uraian_pekerjaan');
    }

    public function test_validation_update_pekerjaan_with_bukti_pekerjaan_required(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['bukti_pekerjaan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }

    public function test_validation_update_pekerjaan_with_bukti_pekerjaan_file(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['bukti_pekerjaan'] = UploadedFile::fake()->create('file.pdfs', 1000, 'text/plain');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }

    public function test_validation_update_pekerjaan_with_bukti_pekerjaan_mimes(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['bukti_pekerjaan'] = UploadedFile::fake()->create('file.exe', 1000, 'application/exe');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }

    public function test_validation_update_pekerjaan_with_bukti_pekerjaan_max(): void
    {
        $this->test_can_create_pekerjaan_with_valid_payload();
        $this->payload['bukti_pekerjaan'] = UploadedFile::fake()->create('file.pdf', 3000, 'application/pdf');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pekerjaan');
    }
}
