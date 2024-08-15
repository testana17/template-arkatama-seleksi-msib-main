<?php

namespace Tests\Feature\Camaba\Formulir07;

use App\Models\Camaba\FormulirF07\PelatihanProfesional;
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

class PelatihanTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $user;

    protected $payload;

    protected $table = 'riwayat_pelatihan';

    protected $model = PelatihanProfesional::class;

    protected $route = 'formulir-f07.pelatihan-profesional.';

    protected $routeParam = 'pelatihan_profesional';

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
            'nama_pelatihan' => 'Pelatihan Profesional',
            'tahun' => 2021,
            'jenis' => 'DN',
            'penyelenggara' => 'Penyelenggara',
            'tempat' => 'Tempat',
            'jangka_waktu' => 1,
            'tanggal_mulai' => '2021-01-01',
            'tanggal_selesai' => '2021-01-02',
            'bukti_pelatihan' => UploadedFile::fake()->create('bukti_pelatihan.pdf', 1000, 'application/pdf'),
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

    public function test_can_create_pelatihan_with_valid_payload(): void
    {
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertOk();
        $model = new $this->model($this->payload);
        $this->assertDatabaseHas($this->table, $model->toArray());
    }

    public function test_can_update_pelatihan_with_valid_payload(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();
        $updatePayload = array_merge($this->payload, [
            'nama_pelatihan' => 'Pelatihan Update',
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

    public function test_can_delete_pelatihan(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();
        $model = $this->model::first();
        $this->actingAs($this->user)
            ->deleteJson(route(
                $this->route.'destroy',
                [$this->routeParam => $model['id']]
            ))
            ->assertOk();

        $this->assertDatabaseMissing($this->table, $model->toArray());
    }

    public function test_can_restore_pelatihan(): void
    {
        $this->test_can_delete_pelatihan();
        $model = $this->model::onlyTrashed()->first();

        $this->actingAs($this->user)
            ->putJson(route(
                $this->route.'restore',
                [$this->routeParam => $model['id']]
            ))
            ->assertOk();
    }

    public function test_validation_create_pelatihan_with_nama_pelatihan_required(): void
    {
        $this->payload['nama_pelatihan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_create_pelatihan_with_nama_pelatihan_alpha_num_spaces_with_alphabet_and_symbol(): void
    {
        $this->payload['nama_pelatihan'] = '@@@';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_create_pelatihan_with_nama_pelatihan_min(): void
    {
        $this->payload['nama_pelatihan'] = 'A';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_create_pelatihan_with_nama_pelatihan_max(): void
    {
        $this->payload['nama_pelatihan'] = str_repeat('A', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_create_pelatihan_with_tahun_required(): void
    {
        $this->payload['tahun'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_pelatihan_with_tahun_numeric(): void
    {
        $this->payload['tahun'] = 'Tahun';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_pelatihan_with_tahun_digits(): void
    {
        $this->payload['tahun'] = 20269;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_create_pelatihan_with_jenis_required(): void
    {
        $this->payload['jenis'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jenis');
    }

    public function test_validation_create_pelatihan_with_jenis_in_array(): void
    {
        $this->payload['jenis'] = 'NU';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jenis');
    }

    public function test_validation_create_pelatihan_with_penyelenggara_required(): void
    {
        $this->payload['jenis'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jenis');
    }

    public function test_validation_create_pelatihan_with_penyelenggara_alpha_num_spaces_with_alphabet_and_symbol(): void
    {
        $this->payload['penyelenggara'] = '@@@';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_create_pelatihan_with_penyelenggara_min(): void
    {
        $this->payload['penyelenggara'] = 'A';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_create_pelatihan_with_penyelenggara_max(): void
    {
        $this->payload['penyelenggara'] = str_repeat('A', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_create_pelatihan_with_tempat_required(): void
    {
        $this->payload['tempat'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_create_pelatihan_with_tempat_alpha_spaces(): void
    {
        $this->payload['tempat'] = '@@@';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_create_pelatihan_with_tempat_min(): void
    {
        $this->payload['tempat'] = 'A';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_create_pelatihan_with_tempat_max(): void
    {
        $this->payload['tempat'] = str_repeat('A', 256);
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_create_pelatihan_with_jangka_waktu_required(): void
    {
        $this->payload['jangka_waktu'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_create_pelatihan_with_jangka_waktu_numeric(): void
    {
        $this->payload['jangka_waktu'] = 'Jangka Waktu';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_create_pelatihan_with_jangka_waktu_min(): void
    {
        $this->payload['jangka_waktu'] = 0;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_create_pelatihan_with_jangka_waktu_max(): void
    {
        $this->payload['jangka_waktu'] = 99999;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_create_pelatihan_with_tanggal_mulai_required(): void
    {
        $this->payload['tanggal_mulai'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_create_pelatihan_with_tanggal_mulai_date(): void
    {
        $this->payload['tanggal_mulai'] = '2069-69-69';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_create_pelatihan_with_tanggal_selesai_required(): void
    {
        $this->payload['tanggal_selesai'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_create_pelatihan_with_tanggal_selesai_date(): void
    {
        $this->payload['tanggal_selesai'] = '2069-69-69';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_create_pelatihan_with_bukti_pelatihan_required(): void
    {
        $this->payload['bukti_pelatihan'] = null;
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pelatihan');
    }

    public function test_validation_create_pelatihan_with_bukti_pelatihan_file(): void
    {
        $this->payload['bukti_pelatihan'] = 'bukti_pelatihan.pdf';
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pelatihan');
    }

    public function test_validation_create_pelatihan_with_bukti_pelatihan_file_max(): void
    {
        $this->payload['bukti_pelatihan'] = UploadedFile::fake()->create('bukti_pelatihan.pdf', 3000, 'application/pdf');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pelatihan');
    }

    public function test_validation_create_pelatihan_with_bukti_pelatihan_mimes(): void
    {
        $this->payload['bukti_pelatihan'] = UploadedFile::fake()->create('bukti_pelatihan.exe', 2000, 'application/exe');
        $this->actingAs($this->user)
            ->postJson(route($this->route.'store'), $this->payload)
            ->assertJsonValidationErrors('bukti_pelatihan');
    }

    public function test_validation_update_pelatihan_with_nama_pelatihan_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['nama_pelatihan'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_update_pelatihan_with_nama_pelatihan_alpha_num_spaces_with_alphabet_and_symbol(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['nama_pelatihan'] = '@@@';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_update_pelatihan_with_nama_pelatihan_min(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['nama_pelatihan'] = 'A';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_update_pelatihan_with_nama_pelatihan_max(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['nama_pelatihan'] = str_repeat('A', 256);
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('nama_pelatihan');
    }

    public function test_validation_update_pelatihan_with_tahun_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tahun'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_pelatihan_with_tahun_numeric(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tahun'] = 'Tahun';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_pelatihan_with_tahun_digits(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tahun'] = 20269;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_validation_update_pelatihan_with_jenis_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['jenis'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jenis');
    }

    public function test_validation_update_pelatihan_with_jenis_in_array(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['jenis'] = 'NU';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jenis');
    }

    public function test_validation_update_pelatihan_with_penyelenggara_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['jenis'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jenis');
    }

    public function test_validation_update_pelatihan_with_penyelenggara_alpha_num_spaces_with_alphabet_and_symbol(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['penyelenggara'] = '@@@';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_update_pelatihan_with_penyelenggara_min(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['penyelenggara'] = 'A';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_update_pelatihan_with_penyelenggara_max(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['penyelenggara'] = str_repeat('A', 256);
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('penyelenggara');
    }

    public function test_validation_update_pelatihan_with_tempat_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tempat'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_update_pelatihan_with_tempat_alpha_spaces(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tempat'] = '@@@';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_update_pelatihan_with_tempat_min(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tempat'] = 'A';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_update_pelatihan_with_tempat_max(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tempat'] = str_repeat('A', 256);
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_validation_update_pelatihan_with_jangka_waktu_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['jangka_waktu'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_update_pelatihan_with_jangka_waktu_numeric(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['jangka_waktu'] = 'Jangka Waktu';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_update_pelatihan_with_jangka_waktu_min(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['jangka_waktu'] = 0;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_update_pelatihan_with_jangka_waktu_max(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['jangka_waktu'] = 99999;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('jangka_waktu');
    }

    public function test_validation_update_pelatihan_with_tanggal_mulai_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tanggal_mulai'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_update_pelatihan_with_tanggal_mulai_date(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tanggal_mulai'] = '2069-69-69';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_mulai');
    }

    public function test_validation_update_pelatihan_with_tanggal_selesai_required(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tanggal_selesai'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_update_pelatihan_with_tanggal_selesai_date(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['tanggal_selesai'] = '2069-69-69';
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_validation_update_pelatihan_with_bukti_pelatihan_file_max(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['bukti_pelatihan'] = UploadedFile::fake()->create('bukti_pelatihan.pdf', 3000, 'application/pdf');
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('bukti_pelatihan');
    }

    public function test_validation_update_pelatihan_with_bukti_pelatihan_mimes(): void
    {
        $this->test_can_create_pelatihan_with_valid_payload();

        $model = $this->model::first();
        $this->payload['bukti_pelatihan'] = UploadedFile::fake()->create('bukti_pelatihan.exe', 2000, 'application/exe');
        $this->actingAs($this->user)
            ->putJson(route($this->route.'update', [$this->routeParam => $model->id]), $this->payload)
            ->assertJsonValidationErrors('bukti_pelatihan');
    }
}
