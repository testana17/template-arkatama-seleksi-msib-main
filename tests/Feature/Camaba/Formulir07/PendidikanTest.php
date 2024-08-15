<?php

namespace Tests\Feature\Camaba\Formulir07;

use App\Models\Camaba\FormulirF07\RiwayatPendidikan;
use App\Models\Master\JenjangPendidikan;
use App\Models\Master\Kecamatan;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\CPM;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\SimpleTest;

class PendidikanTest extends SimpleTest
{
    protected $table = 'riwayat_pendidikan';

    protected $model = RiwayatPendidikan::class;

    protected $route = 'formulir-f07.riwayat-pendidikan.';

    protected $routeParam = 'riwayat_pendidikan';

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
        $kecamatan = Kecamatan::first();
        $this->payload = [
            'formulir_id' => $formulir->id,
            'nama_institusi' => 'SMAN 1 Jakarta',
            'jenjang_pendidikan_id' => JenjangPendidikan::where('kode', 'SMA')->value('id'),
            'tahun_lulus' => 2021,
            'jurusan' => 'IPA',
            'bukti_ijazah' => UploadedFile::fake()->create('file.png', 1000, 'image/png'),
        ];

        RiwayatPendidikan::create($this->payload);
    }

    public function test_user_cannot_access_the_page_if_register_formulir_has_not_completed()
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

    public function test_can_create_pendidikan_with_valid_payload(): void
    {
        $this->testCreate(assertDbHasExcept: ['bukti_ijazah']);
    }

    public function test_insert_validation_nama_institusi_is_required(): void
    {
        $this->testCreate([
            'nama_institusi' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_insert_validation_nama_institusi_alpha_num_spaces(): void
    {
        $this->testCreate([
            'nama_institusi' => '@!!E',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_insert_validation_nama_institusi_min_3_characters(): void
    {
        $this->testCreate([
            'nama_institusi' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_insert_validation_nama_institusi_max_255_characters(): void
    {
        $this->testCreate([
            'nama_institusi' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_insert_validation_jenjang_pendidikan_id_is_required(): void
    {
        $this->testCreate([
            'jenjang_pendidikan_id' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_insert_validation_jenjang_pendidikan_id_is_exists(): void
    {
        $this->testCreate([
            'jenjang_pendidikan_id' => 'asda',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_insert_validation_tahun_lulus_is_required(): void
    {
        $this->testCreate([
            'tahun_lulus' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_insert_validation_tahun_lulus_must_be_numeric(): void
    {
        $this->testCreate([
            'tahun_lulus' => 'abc',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_insert_validation_tahun_lulus_must_be_4_digit(): void
    {
        $this->testCreate([
            'tahun_lulus' => '202',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_insert_validation_tahun_lulus_min(): void
    {
        $this->testCreate([
            'tahun_lulus' => 1899,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_insert_validation_tahun_lulus_max(): void
    {
        $this->testCreate([
            'tahun_lulus' => date('Y') + 101,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_insert_validation_jurusan_is_required(): void
    {
        $this->testCreate([
            'jurusan' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_insert_validation_jurusan_cannot_only_symbol(): void
    {
        $this->testCreate([
            'jurusan' => '!!!!',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_insert_validation_jurusan_cannot_only_number(): void
    {
        $this->testCreate([
            'jurusan' => '121',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_insert_validation_jurusan_min_3_characters(): void
    {
        $this->testCreate([
            'jurusan' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_insert_validation_jurusan_max_255_characters(): void
    {
        $this->testCreate([
            'jurusan' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_insert_validation_bukti_ijazah_is_required(): void
    {
        $this->testCreate([
            'bukti_ijazah' => null,
        ], 422)
            ->assertJsonValidationErrors('bukti_ijazah');
    }

    public function test_insert_validation_bukti_ijazah_must_be_file(): void
    {
        $this->testCreate([
            'bukti_ijazah' => 'bukti_ijazah',
        ], 422)
            ->assertJsonValidationErrors('bukti_ijazah');
    }

    public function test_insert_validation_bukti_ijazah_max_2048_kb(): void
    {
        $this->testCreate([
            'bukti_ijazah' => UploadedFile::fake()->create('bukti_ijazah.pdf', 3000, 'application/pdf'),
        ], 422)
            ->assertJsonValidationErrors('bukti_ijazah');
    }

    public function test_insert_validation_bukti_ijazah_must_be_pdf_jpg_jpeg_png(): void
    {
        $this->testCreate([
            'bukti_ijazah' => UploadedFile::fake()->create('bukti_ijazah.doc', 1000, 'application/msword'),
        ], 422)
            ->assertJsonValidationErrors('bukti_ijazah');
    }

    public function test_can_update_pendidikan_with_valid_payload(): void
    {
        $this->testUpdate([
            'nama_institusi' => 'SMAN 2 Malang',
            'tahun_lulus' => 2019,
        ], assertDbHasExcept: ['bukti_ijazah']);
    }

    public function test_update_validation_nama_institusi_is_required(): void
    {
        $this->testUpdate([
            'nama_institusi' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_update_validation_nama_institusi_min_3_characters(): void
    {
        $this->testUpdate([
            'nama_institusi' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_update_validation_nama_institusi_max_255_characters(): void
    {
        $this->testUpdate([
            'nama_institusi' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_update_validation_nama_institusi_alpha_num_spaces(): void
    {
        $this->testUpdate([
            'nama_institusi' => '@!!E',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('nama_institusi');
    }

    public function test_update_validation_jenjang_pendidikan_id_is_required(): void
    {
        $this->testUpdate([
            'jenjang_pendidikan_id' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_update_validation_jenjang_pendidikan_id_is_exists(): void
    {
        $this->testUpdate([
            'jenjang_pendidikan_id' => 'asda',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_update_validation_tahun_lulus_is_required(): void
    {
        $this->testUpdate([
            'tahun_lulus' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_update_validation_tahun_lulus_must_be_numeric(): void
    {
        $this->testUpdate([
            'tahun_lulus' => 'abc',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_update_validation_tahun_lulus_must_be_4_digit(): void
    {
        $this->testUpdate([
            'tahun_lulus' => '202',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_update_validation_tahun_lulus_min(): void
    {
        $this->testUpdate([
            'tahun_lulus' => 1899,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_update_validation_tahun_lulus_max(): void
    {
        $this->testUpdate([
            'tahun_lulus' => date('Y') + 101,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('tahun_lulus');
    }

    public function test_update_validation_jurusan_is_required(): void
    {
        $this->testUpdate([
            'jurusan' => null,
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_update_validation_jurusan_cannot_only_symbol(): void
    {
        $this->testUpdate([
            'jurusan' => '!!!!',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_update_validation_jurusan_cannot_only_number(): void
    {
        $this->testUpdate([
            'jurusan' => '121',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_update_validation_jurusan_min_3_characters(): void
    {
        $this->testUpdate([
            'jurusan' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_update_validation_jurusan_max_255_characters(): void
    {
        $this->testUpdate([
            'jurusan' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_ijazah'])
            ->assertJsonValidationErrors('jurusan');
    }

    public function test_update_validation_bukti_ijazah_is_required(): void
    {
        $this->testUpdate([
            'bukti_ijazah' => null,
        ], 422)
            ->assertJsonValidationErrors('bukti_ijazah');
    }

    public function test_update_validation_bukti_ijazah_max_2048_kb(): void
    {
        $this->testUpdate([
            'bukti_ijazah' => UploadedFile::fake()->create('bukti_ijazah.pdf', 3000, 'application/pdf'),
        ], 422)
            ->assertJsonValidationErrors('bukti_ijazah');
    }

    public function test_update_validation_bukti_ijazah_must_be_pdf_jpg_jpeg_png(): void
    {
        $this->testUpdate([
            'bukti_ijazah' => UploadedFile::fake()->create('bukti_ijazah.doc', 1000, 'application/msword'),
        ], 422)
            ->assertJsonValidationErrors('bukti_ijazah');
    }

    public function test_can_delete_pendidikan(): void
    {
        $this->testDelete(true);
    }

    public function test_can_restore_pendidikan(): void
    {
        $this->testRestore();
    }
}
