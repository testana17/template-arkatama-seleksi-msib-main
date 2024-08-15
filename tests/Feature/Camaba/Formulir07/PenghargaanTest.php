<?php

namespace Tests\Feature\Camaba\Formulir07;

use App\Models\Camaba\FormulirF07\Penghargaan;
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

class PenghargaanTest extends SimpleTest
{
    protected $table = 'riwayat_penghargaan';

    protected $model = Penghargaan::class;

    protected $route = 'formulir-f07.penghargaan.';

    protected $routeParam = 'penghargaan';

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
            'tahun' => '2021',
            'nama_penghargaan' => 'Juara 1 Lomba',
            'pemberi' => 'Pemberi Penghargaan',
            'tingkat' => 'nasional',
            'bukti_penghargaan' => UploadedFile::fake()->create('bukti_penghargaan.pdf', 1000, 'application/pdf'),
        ];

        Penghargaan::create($this->payload);
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

    public function test_can_create_penghargaan_with_valid_payload(): void
    {
        $this->testCreate(assertDbHasExcept: ['bukti_penghargaan']);
    }

    public function test_insert_validation_nama_penghargaan_is_required(): void
    {
        $this->testCreate([
            'nama_penghargaan' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_insert_validation_nama_penghargaan_min_3_characters(): void
    {
        $this->testCreate([
            'nama_penghargaan' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_insert_validation_nama_penghargaan_max_255_characters(): void
    {
        $this->testCreate([
            'nama_penghargaan' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_insert_validation_nama_penghargaan_cannot_only_number(): void
    {
        $this->testCreate([
            'nama_penghargaan' => '123',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_insert_validation_nama_penghargaan_cannot_only_symbol(): void
    {
        $this->testCreate([
            'nama_penghargaan' => '!!!',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_insert_validation_nama_penghargaan_can_contains_combination_of_alphabet_and_symbol(): void
    {
        $this->testCreate([
            'nama_penghargaan' => 'Juara 1 Lomba',
        ], assertDbHasExcept: ['bukti_penghargaan']);
    }

    public function test_insert_validation_tahun_is_required(): void
    {
        $this->testCreate([
            'tahun' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tahun');
    }

    public function test_insert_validation_tahun_must_be_numeric(): void
    {
        $this->testCreate([
            'tahun' => 'abc',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tahun');
    }

    public function test_insert_validation_tahun_must_be_4_digit(): void
    {
        $this->testCreate([
            'tahun' => '202',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tahun');
    }

    public function test_insert_validation_pemberi_is_required(): void
    {
        $this->testCreate([
            'pemberi' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_insert_validation_pemberi_can_only_contains_alphabet_and_space(): void
    {
        $this->testCreate([
            'pemberi' => 'Pemberi 123',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_insert_validation_pemberi_min_3_characters(): void
    {
        $this->testCreate([
            'pemberi' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_insert_validation_pemberi_max_255_characters(): void
    {
        $this->testCreate([
            'pemberi' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_insert_validation_tingkat_is_required(): void
    {
        $this->testCreate([
            'tingkat' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_insert_validation_tingkat_must_be_in_enum(): void
    {
        $this->testCreate([
            'tingkat' => 'kab',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_insert_validation_bukti_penghargaan_is_required(): void
    {
        $this->testCreate([
            'bukti_penghargaan' => null,
        ], 422)
            ->assertJsonValidationErrors('bukti_penghargaan');
    }

    public function test_insert_validation_bukti_penghargaan_must_be_file(): void
    {
        $this->testCreate([
            'bukti_penghargaan' => 'bukti_penghargaan',
        ], 422)
            ->assertJsonValidationErrors('bukti_penghargaan');
    }

    public function test_insert_validation_bukti_penghargaan_max_2048_kb(): void
    {
        $this->testCreate([
            'bukti_penghargaan' => UploadedFile::fake()->create('bukti_penghargaan.pdf', 3000, 'application/pdf'),
        ], 422)
            ->assertJsonValidationErrors('bukti_penghargaan');
    }

    public function test_insert_validation_bukti_penghargaan_must_be_pdf_jpg_jpeg_png(): void
    {
        $this->testCreate([
            'bukti_penghargaan' => UploadedFile::fake()->create('bukti_penghargaan.doc', 1000, 'application/msword'),
        ], 422)
            ->assertJsonValidationErrors('bukti_penghargaan');
    }

    public function test_can_update_penghargaan_with_valid_payload(): void
    {
        $this->testUpdate([
            'tahun' => '2022',
            'nama_penghargaan' => 'Juara 2 Lomba',
            'pemberi' => 'GG GEMING',
        ], assertDbHasExcept: ['bukti_penghargaan']);
    }

    public function test_update_validation_nama_penghargaan_is_required(): void
    {
        $this->testUpdate([
            'nama_penghargaan' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_update_validation_nama_penghargaan_min_3_characters(): void
    {
        $this->testUpdate([
            'nama_penghargaan' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_update_validation_nama_penghargaan_max_255_characters(): void
    {
        $this->testUpdate([
            'nama_penghargaan' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_update_validation_nama_penghargaan_cannot_only_number(): void
    {
        $this->testUpdate([
            'nama_penghargaan' => '123',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_update_validation_nama_penghargaan_cannot_only_symbol(): void
    {
        $this->testUpdate([
            'nama_penghargaan' => '!!!',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('nama_penghargaan');
    }

    public function test_update_validation_nama_penghargaan_can_contains_combination_of_alphabet_and_symbol(): void
    {
        $this->testUpdate([
            'nama_penghargaan' => 'Juara 1 Lomba',
        ], assertDbHasExcept: ['bukti_penghargaan']);
    }

    public function test_update_validation_tahun_is_required(): void
    {
        $this->testUpdate([
            'tahun' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tahun');
    }

    public function test_update_validation_tahun_must_be_numeric(): void
    {
        $this->testUpdate([
            'tahun' => 'abc',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tahun');
    }

    public function test_update_validation_tahun_must_be_4_digit(): void
    {
        $this->testUpdate([
            'tahun' => '202',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tahun');
    }

    public function test_update_validation_pemberi_is_required(): void
    {
        $this->testUpdate([
            'pemberi' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_update_validation_pemberi_can_only_contains_alphabet_and_space(): void
    {
        $this->testUpdate([
            'pemberi' => 'Pemberi 123',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_update_validation_pemberi_min_3_characters(): void
    {
        $this->testUpdate([
            'pemberi' => 'ab',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_update_validation_pemberi_max_255_characters(): void
    {
        $this->testUpdate([
            'pemberi' => str_repeat('a', 256),
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('pemberi');
    }

    public function test_update_validation_tingkat_is_required(): void
    {
        $this->testUpdate([
            'tingkat' => null,
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_update_validation_tingkat_must_be_in_enum(): void
    {
        $this->testUpdate([
            'tingkat' => 'kab',
        ], 422, assertDbHasExcept: ['bukti_penghargaan'])
            ->assertJsonValidationErrors('tingkat');
    }

    public function test_update_validation_bukti_penghargaan_is_required(): void
    {
        $this->testUpdate([
            'bukti_penghargaan' => null,
        ], 422)
            ->assertJsonValidationErrors('bukti_penghargaan');
    }

    public function test_update_validation_bukti_penghargaan_max_2048_kb(): void
    {
        $this->testUpdate([
            'bukti_penghargaan' => UploadedFile::fake()->create('bukti_penghargaan.pdf', 3000, 'application/pdf'),
        ], 422)
            ->assertJsonValidationErrors('bukti_penghargaan');
    }

    public function test_update_validation_bukti_penghargaan_must_be_pdf_jpg_jpeg_png(): void
    {
        $this->testUpdate([
            'bukti_penghargaan' => UploadedFile::fake()->create('bukti_penghargaan.doc', 1000, 'application/msword'),
        ], 422)
            ->assertJsonValidationErrors('bukti_penghargaan');
    }

    public function test_can_delete_penghargaan(): void
    {
        $this->testDelete(true);
    }

    public function test_can_restore_penghargaan(): void
    {
        $this->testRestore();
    }
}
