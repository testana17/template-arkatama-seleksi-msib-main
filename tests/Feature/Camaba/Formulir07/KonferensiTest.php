<?php

namespace Tests\Feature\Camaba\Formulir07;

use App\Models\Camaba\FormulirF07\RiwayatSeminar;
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

class KonferensiTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $user;

    protected $payload;

    protected $table = 'riwayat_seminar';

    protected $model = RiwayatSeminar::class;

    protected $route = 'formulir-f07.konferensi.';

    protected $routeParam = 'konferensi';

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

        SyaratPendaftaran::where('prodi_id', $formulir->pilihan_prodi_id)
            ->each(function ($syarat) use ($formulir) {
                FormulirBerkasPersyaratan::create([
                    'formulir_id' => $formulir->id,
                    'keterangan' => 'Keterangan',
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
            'judul' => 'Seminar Nasional Arkatama',
            'tahun' => 2021,
            'jenis_kegiatan' => 'seminar',
            'tanggal_mulai' => '2021-01-01',
            'tanggal_selesai' => '2021-01-02',
            'penyelenggara' => 'Arkatama',
            'tempat' => 'Graha Arkatama',
            'peran' => 'Peserta',
            'bukti_seminar' => UploadedFile::fake()->create('file.png', 1000, 'image/png'),
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

    public function test_can_create_konferensi_with_valid_payload(): void
    {
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertCreated();
        $model = new $this->model($this->payload);
        $this->assertDatabaseHas($this->table, $model->toArray());
    }

    public function test_can_update_konferensi_with_valid_payload(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $updatePayload = array_merge($this->payload, [
            'judul' => 'Seminar Nasional Arkatama 123',
            'tahun' => 2024,
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

    public function test_can_delete_konferensi(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->deleteJson(route(
                $this->route.'destroy',
                [$this->routeParam => $model['id']]
            ))
            ->assertOk();

        $this->assertDatabaseMissing($this->table, $model->toArray());
    }

    public function test_can_restore_konferensi(): void
    {
        $this->test_can_delete_konferensi();
        $model = $this->model::onlyTrashed()->first();

        $this->actingAs($this->user)
            ->putJson(route(
                $this->route.'restore',
                [$this->routeParam => $model['id']]
            ))
            ->assertOk();
    }

    public function test_validation_create_konferensi_with_judul_required(): void
    {
        $this->payload['judul'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_create_konferensi_with_judul_string(): void
    {
        $this->payload['judul'] = 123;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_create_konferensi_with_judul_min(): void
    {
        $this->payload['judul'] = 'a';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_create_konferensi_with_judul_max(): void
    {
        $this->payload['judul'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_create_konferensi_with_tahun_required(): void
    {
        $this->payload['tahun'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_konferensi_with_tahun_numeric(): void
    {
        $this->payload['tahun'] = 'a';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_konferensi_with_tahun_digits(): void
    {
        $this->payload['tahun'] = 12345;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_konferensi_with_tahun_min(): void
    {
        $this->payload['tahun'] = 1899;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_konferensi_with_tahun_max(): void
    {
        $this->payload['tahun'] = date('Y') + 101;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_konferensi_with_jenis_kegiatan_required(): void
    {
        $this->payload['jenis_kegiatan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jenis_kegiatan');
    }

    public function test_validation_create_konferensi_with_jenis_kegiatan_in(): void
    {
        $this->payload['jenis_kegiatan'] = 'seminar nasional';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jenis_kegiatan');
    }

    public function test_validation_create_konferensi_with_tanggal_mulai_required(): void
    {
        $this->payload['tanggal_mulai'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_create_konferensi_with_tanggal_mulai_date(): void
    {
        $this->payload['tanggal_mulai'] = 'a';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_create_konferensi_with_tanggal_mulai_before_or_equal(): void
    {
        $this->payload['tanggal_mulai'] = '2021-01-02';
        $this->payload['tanggal_selesai'] = '2021-01-01';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_create_konferensi_with_tanggal_selesai_required(): void
    {
        $this->payload['tanggal_selesai'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_create_konferensi_with_tanggal_selesai_date(): void
    {
        $this->payload['tanggal_selesai'] = 'a';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_create_konferensi_with_tanggal_selesai_after_or_equal(): void
    {
        $this->payload['tanggal_mulai'] = '2021-01-02';
        $this->payload['tanggal_selesai'] = '2021-01-01';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_create_konferensi_with_penyelenggara_required(): void
    {
        $this->payload['penyelenggara'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_create_konferensi_with_penyelenggara_min(): void
    {
        $this->payload['penyelenggara'] = 'a';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_create_konferensi_with_penyelenggara_max(): void
    {
        $this->payload['penyelenggara'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_create_konferensi_with_tempat_required(): void
    {
        $this->payload['tempat'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_create_konferensi_with_tempat_min(): void
    {
        $this->payload['tempat'] = 'a';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_create_konferensi_with_tempat_max(): void
    {
        $this->payload['tempat'] = str_repeat('a', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_create_konferensi_with_peran_required(): void
    {
        $this->payload['peran'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('peran');
    }

    public function test_validation_create_konferensi_with_peran_in(): void
    {
        $this->payload['peran'] = 'Pembimbing';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('peran');
    }

    public function test_validation_create_konferensi_with_bukti_seminar_required(): void
    {
        $this->payload['bukti_seminar'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }

    public function test_validation_create_konferensi_with_bukti_seminar_file(): void
    {
        $this->payload['bukti_seminar'] = UploadedFile::fake()->create('file.pdfs', 1000, 'text/plain');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }

    public function test_validation_create_konferensi_with_bukti_seminar_mimes(): void
    {
        $this->payload['bukti_seminar'] = UploadedFile::fake()->create('file.exe', 1000, 'application/exe');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }

    public function test_validation_create_konferensi_with_bukti_seminar_max(): void
    {
        $this->payload['bukti_seminar'] = UploadedFile::fake()->create('file.pdf', 3000, 'application/pdf');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }

    public function test_validation_update_konferensi_with_judul_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();

        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['judul'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_update_konferensi_with_judul_string(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();

        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['judul'] = 1232;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_update_konferensi_with_judul_min(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['judul'] = 'a';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_update_konferensi_with_judul_max(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['judul'] = str_repeat('a', 256);
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('judul');
    }

    public function test_validation_update_konferensi_with_tahun_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tahun'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_konferensi_with_tahun_numeric(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tahun'] = 'a';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_konferensi_with_tahun_digits(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tahun'] = 12345;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_konferensi_with_tahun_min(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tahun'] = 1899;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_konferensi_with_tahun_max(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tahun'] = date('Y') + 101;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_konferensi_with_jenis_kegiatan_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['jenis_kegiatan'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jenis_kegiatan');
    }

    public function test_validation_update_konferensi_with_jenis_kegiatan_in(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['jenis_kegiatan'] = 'seminar nasional';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jenis_kegiatan');
    }

    public function test_validation_update_konferensi_with_tanggal_mulai_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_mulai'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_update_konferensi_with_tanggal_mulai_date(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_mulai'] = 'a';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_update_konferensi_with_tanggal_mulai_before_or_equal(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_mulai'] = '2021-01-02';
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_selesai'] = '2021-01-01';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_update_konferensi_with_tanggal_selesai_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_selesai'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_update_konferensi_with_tanggal_selesai_date(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_selesai'] = 'a';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_update_konferensi_with_tanggal_selesai_after_or_equal(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_mulai'] = '2021-01-02';
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tanggal_selesai'] = '2021-01-01';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_update_konferensi_with_penyelenggara_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['penyelenggara'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_update_konferensi_with_penyelenggara_min(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['penyelenggara'] = 'a';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_update_konferensi_with_penyelenggara_max(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['penyelenggara'] = str_repeat('a', 256);
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_update_konferensi_with_tempat_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tempat'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_update_konferensi_with_tempat_min(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tempat'] = 'a';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_update_konferensi_with_tempat_max(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['tempat'] = str_repeat('a', 256);
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_update_konferensi_with_peran_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['peran'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('peran');
    }

    public function test_validation_update_konferensi_with_peran_in(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['peran'] = 'Pembimbing';
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('peran');
    }

    public function test_validation_update_konferensi_with_bukti_seminar_required(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['bukti_seminar'] = null;
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }

    public function test_validation_update_konferensi_with_bukti_seminar_file(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['bukti_seminar'] = UploadedFile::fake()->create('file.pdfs', 1000, 'text/plain');
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }

    public function test_validation_update_konferensi_with_bukti_seminar_mimes(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['bukti_seminar'] = UploadedFile::fake()->create('file.exe', 1000, 'application/exe');
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }

    public function test_validation_update_konferensi_with_bukti_seminar_max(): void
    {
        $this->test_can_create_konferensi_with_valid_payload();
        $this->payload['bukti_seminar'] = UploadedFile::fake()->create('file.pdf', 3000, 'application/pdf');
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('bukti_seminar');
    }
}
