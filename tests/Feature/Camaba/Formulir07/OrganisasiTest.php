<?php

namespace Tests\Feature\Camaba\Formulir07;

use App\Models\Camaba\FormulirF07\OrganisasiProfesi;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\CPM;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\SimpleTest;

class OrganisasiTest extends SimpleTest
{
    protected $table = 'riwayat_organisasi';

    protected $model = OrganisasiProfesi::class;

    protected $route = 'formulir-f07.organisasi-profesi.';

    protected $routeParam = 'organisasi_profesi';

    public function setUp(): void
    {
        parent::setUp();
        $pembayaran = Pembayaran::factory()->create();
        $formulir = $pembayaran->register->formulir;
        $formulir->register->update(['nomor_telepon' => '628123456789']);
        $formulir->update([
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan Baru',
            'kabupaten_kota_id' => 1,
            'nomor_telepon' => '628123456789',
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
        $this->payload = [
            'formulir_id' => $formulir->id,
            'tahun' => '2021',
            'nama_organisasi' => 'Organisasi Profesi',
            'jabatan' => 'Ketua',
            'tingkat' => 'nasional',
            'tempat' => 'Jakarta',
            'bukti_organisasi' => UploadedFile::fake()->create('bukti-organisasi.pdf', 1000, 'application/pdf'),
        ];

        OrganisasiProfesi::create($this->payload);
    }

    public function test_user_cannot_access_the_page_if_regsiter_formulir_has_not_completed()
    {
        $this->user->formulir->pembayaran->update(['status' => 'belum lunas']);
        FormulirMatakuliahCPM::where('formulir_id', $this->user->formulir->id)->delete();
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(302); // Redirect to dashboard
    }

    public function test_user_can_access_the_page()
    {
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $response = $this->getJson(route($this->route.'index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_entry_histories_must_be_returned(): void
    {
        $response = $this->getJson(route($this->route.'histori'), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);
        $response->assertJsonStructure(['data']);
    }

    public function test_can_create_organisasi_profesi_with_valid_payload(): void
    {
        $this->testCreate(assertDbHasExcept: ['bukti_organisasi']);
    }

    public function test_insert_validation_nama_organisasi_is_required(): void
    {
        $this->testCreate([
            'nama_organisasi' => null,
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_insert_validation_nama_organisasi_min_3_characters(): void
    {
        $this->testCreate([
            'nama_organisasi' => 'ab',
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_insert_validation_nama_organisasi_max_255_characters(): void
    {
        $this->testCreate([
            'nama_organisasi' => str_repeat('a', 256),
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_insert_validation_nama_organisasi_alpha_num_spaces_with_alphabet_and_symbol(): void
    {
        $this->testCreate([
            'nama_organisasi' => '123',
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_insert_validation_tahun_is_required(): void
    {
        $this->testCreate([
            'tahun' => null,
        ], 422)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_insert_validation_tahun_must_be_numeric(): void
    {
        $this->testCreate([
            'tahun' => 'abc',
        ], 422)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_insert_validation_tahun_must_be_4_digit(): void
    {
        $this->testCreate([
            'tahun' => '202',
        ], 422)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_insert_validation_tingkat_is_required(): void
    {
        $this->testCreate([
            'tingkat' => null,
        ], 422)
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_insert_validation_tingkat_must_be_in_enum(): void
    {
        $this->testCreate([
            'tingkat' => 'kab',
        ], 422)
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_insert_validation_jabatan_is_required(): void
    {
        $this->testCreate([
            'jabatan' => null,
        ], 422)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_insert_validation_jabatan_min_3_characters(): void
    {
        $this->testCreate([
            'jabatan' => 'ab',
        ], 422)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_insert_validation_jabatan_max_255_characters(): void
    {
        $this->testCreate([
            'jabatan' => str_repeat('a', 256),
        ], 422)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_insert_validation_tempat_is_required(): void
    {
        $this->testCreate([
            'tempat' => null,
        ], 422)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_insert_validation_tempat_min_3_characters(): void
    {
        $this->testCreate([
            'tempat' => 'ab',
        ], 422)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_insert_validation_tempat_max_255_characters(): void
    {
        $this->testCreate([
            'tempat' => str_repeat('a', 256),
        ], 422)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_insert_validation_bukti_organisasi_is_required(): void
    {
        $this->testCreate([
            'bukti_organisasi' => null,
        ], 422)
            ->assertJsonValidationErrors('bukti_organisasi');
    }

    public function test_insert_validation_bukti_organisasi_max_2048_kb(): void
    {
        $this->testCreate([
            'bukti_organisasi' => UploadedFile::fake()->create('bukti_organisasi.pdf', 3000, 'application/pdf'),
        ], 422)
            ->assertJsonValidationErrors('bukti_organisasi');
    }

    public function test_insert_validation_bukti_organisasi_must_be_pdf_jpg_jpeg_png(): void
    {
        $this->testCreate([
            'bukti_organisasi' => UploadedFile::fake()->create('bukti_organisasi.doc', 1000, 'application/msword'),
        ], 422)
            ->assertJsonValidationErrors('bukti_organisasi');
    }

    public function test_update_validation_nama_organisasi_is_required(): void
    {
        $this->testUpdate([
            'nama_organisasi' => null,
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_update_validation_nama_organisasi_min_3_characters(): void
    {
        $this->testUpdate([
            'nama_organisasi' => 'ab',
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_update_validation_nama_organisasi_max_255_characters(): void
    {
        $this->testUpdate([
            'nama_organisasi' => str_repeat('a', 256),
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_update_validation_nama_organisasi_alpha_num_spaces_with_alphabet_and_symbol(): void
    {
        $this->testUpdate([
            'nama_organisasi' => '123',
        ], 422)
            ->assertJsonValidationErrors('nama_organisasi');
    }

    public function test_update_validation_tahun_is_required(): void
    {
        $this->testUpdate([
            'tahun' => null,
        ], 422)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_update_validation_tahun_must_be_numeric(): void
    {
        $this->testUpdate([
            'tahun' => 'abc',
        ], 422)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_update_validation_tahun_must_be_4_digit(): void
    {
        $this->testUpdate([
            'tahun' => '202',
        ], 422)
            ->assertJsonValidationErrors('tahun');
    }

    public function test_update_validation_tingkat_is_required(): void
    {
        $this->testUpdate([
            'tingkat' => null,
        ], 422)
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_update_validation_tingkat_must_be_in_enum(): void
    {
        $this->testUpdate([
            'tingkat' => 'kab',
        ], 422)
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_update_validation_jabatan_is_required(): void
    {
        $this->testUpdate([
            'jabatan' => null,
        ], 422)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_update_validation_jabatan_min_3_characters(): void
    {
        $this->testUpdate([
            'jabatan' => 'ab',
        ], 422)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_update_validation_jabatan_max_255_characters(): void
    {
        $this->testUpdate([
            'jabatan' => str_repeat('a', 256),
        ], 422)
            ->assertJsonValidationErrors('jabatan');
    }

    public function test_update_validation_tempat_is_required(): void
    {
        $this->testUpdate([
            'tempat' => null,
        ], 422)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_update_validation_tempat_min_3_characters(): void
    {
        $this->testUpdate([
            'tempat' => 'ab',
        ], 422)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_update_validation_tempat_max_255_characters(): void
    {
        $this->testUpdate([
            'tempat' => str_repeat('a', 256),
        ], 422)
            ->assertJsonValidationErrors('tempat');
    }

    public function test_update_validation_bukti_organisasi_is_required(): void
    {
        $this->testUpdate([
            'bukti_organisasi' => null,
        ], 422)
            ->assertJsonValidationErrors('bukti_organisasi');
    }

    public function test_update_validation_bukti_organisasi_max_2048_kb(): void
    {
        $this->testUpdate([
            'bukti_organisasi' => UploadedFile::fake()->create('bukti_organisasi.pdf', 3000, 'application/pdf'),
        ], 422)
            ->assertJsonValidationErrors('bukti_organisasi');
    }

    public function test_update_validation_bukti_organisasi_must_be_pdf_jpg_jpeg_png(): void
    {
        $this->testUpdate([
            'bukti_organisasi' => UploadedFile::fake()->create('bukti_organisasi.doc', 1000, 'application/msword'),
        ], 422)
            ->assertJsonValidationErrors('bukti_organisasi');
    }
}
