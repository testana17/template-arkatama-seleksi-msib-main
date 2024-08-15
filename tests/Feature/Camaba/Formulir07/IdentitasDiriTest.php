<?php

namespace Tests\Feature\Camaba\Formulir07;

use App\Models\Master\Kecamatan;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\CPM;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\SimpleTest;

class IdentitasDiriTest extends SimpleTest
{
    protected $table = 'formulirs';

    protected $model = Formulir::class;

    protected $route = 'formulir-f07.identitas-diri.';

    protected $routeParam = 'identitas_diri';

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
            'nama_lengkap' => 'Nama Lengkap',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Jalan Baru',
            'provinsi_id' => $kecamatan->kabKota->provinsi->id,
            'kabupaten_kota_id' => $kecamatan->kabKota->id,
            'kecamatan_id' => $kecamatan->id,
            'kode_pos' => '12345',
            'nama_kantor' => 'Kantor',
            'alamat_kantor' => 'Jl. Kantor',
            'telepon_kantor' => '628123456789',
            'jabatan' => 'Jabatan',
            'status_pekerjaan' => 'pegawai tetap',
            'nomor_telepon' => '628123456789',
            'pendidikan_terakhir' => 'SMA/SMK',
            'nama_instansi_pendidikan' => 'SMAN 1 Jakarta',
            'jurusan' => 'IPA',
            'tahun_lulus' => '2018',
        ];
    }

    public function test_user_cannot_access_identitas_diri_if_regsiter_formulir_has_not_completed()
    {
        $this->user->formulir->pembayaran->update(['status' => 'belum lunas']);
        FormulirMatakuliahCPM::where('formulir_id', $this->user->formulir->id)->delete();
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(302); // Redirect to dashboard
    }

    public function test_user_can_access_identitas_diri_page()
    {
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(200);
    }

    public function test_user_can_update_identitas_diri()
    {
        $this->testCreate(changePayload: [
            'nama_lengkap' => 'Kobo Kanaeru',
        ]);
    }

    public function test_validation_nama_lengkap_required()
    {
        $this->testCreate(changePayload: [
            'nama_lengkap' => null,
        ], status: 422)->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_validation_nama_lengkap_cannot_contains_numeric()
    {
        $this->testCreate(changePayload: [
            'nama_lengkap' => '123',
        ], status: 422)->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_validation_nama_lengkap_cannot_contains_special_character()
    {
        $this->testCreate(changePayload: [
            'nama_lengkap' => 'Kobo Kanaeru!',
        ], status: 422)->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_validation_nama_lengkap_min_3()
    {
        $this->testCreate(changePayload: [
            'nama_lengkap' => 'Ko',
        ], status: 422)->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_validation_nama_lengkap_max_200()
    {
        $this->testCreate(changePayload: [
            'nama_lengkap' => str_repeat('a', 201),
        ], status: 422)->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_validation_jenis_kelamin_required()
    {
        $this->testCreate(changePayload: [
            'jenis_kelamin' => null,
        ], status: 422)->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_validation_jenis_kelamin_only_L_or_P()
    {
        $this->testCreate(changePayload: [
            'jenis_kelamin' => 'X',
        ], status: 422)->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_validation_tempat_lahir_required()
    {
        $this->testCreate(changePayload: [
            'tempat_lahir' => null,
        ], status: 422)->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_validation_tempat_lahir_cannot_contains_numeric()
    {
        $this->testCreate(changePayload: [
            'tempat_lahir' => '123',
        ], status: 422)->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_validation_tempat_lahir_cannot_contains_special_character()
    {
        $this->testCreate(changePayload: [
            'tempat_lahir' => 'Jakarta!',
        ], status: 422)->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_validation_tempat_lahir_min_3()
    {
        $this->testCreate(changePayload: [
            'tempat_lahir' => 'Ja',
        ], status: 422)->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_validation_tempat_lahir_max_100()
    {
        $this->testCreate(changePayload: [
            'tempat_lahir' => str_repeat('a', 101),
        ], status: 422)->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_validation_tanggal_lahir_required()
    {
        $this->testCreate(changePayload: [
            'tanggal_lahir' => null,
        ], status: 422)->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_validation_tanggal_lahir_is_valid_date()
    {
        $this->testCreate(changePayload: [
            'tanggal_lahir' => 'not-date',
        ], status: 422)->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_validation_kebangsaan_required()
    {
        $this->testCreate(changePayload: [
            'kebangsaan' => null,
        ], status: 422)->assertJsonValidationErrors('kebangsaan');
    }

    public function test_validation_kebangsaan_only_WNI_or_WNA()
    {
        $this->testCreate(changePayload: [
            'kebangsaan' => 'X',
        ], status: 422)->assertJsonValidationErrors('kebangsaan');
    }

    public function test_validation_status_pernikahan_required()
    {
        $this->testCreate(changePayload: [
            'status_pernikahan' => null,
        ], status: 422)->assertJsonValidationErrors('status_pernikahan');
    }

    public function test_validation_status_pernikahan_only_lajang_menikah_pernah_menikah()
    {
        $this->testCreate(changePayload: [
            'status_pernikahan' => 'X',
        ], status: 422)->assertJsonValidationErrors('status_pernikahan');
    }

    public function test_validation_alamat_required()
    {
        $this->testCreate(changePayload: [
            'alamat' => null,
        ], status: 422)->assertJsonValidationErrors('alamat');
    }

    public function test_validation_alamat_min_3()
    {
        $this->testCreate(changePayload: [
            'alamat' => 'Ja',
        ], status: 422)->assertJsonValidationErrors('alamat');
    }

    public function test_validation_alamat_max_200()
    {
        $this->testCreate(changePayload: [
            'alamat' => str_repeat('a', 201),
        ], status: 422)->assertJsonValidationErrors('alamat');
    }

    public function test_validation_provinsi_id_required()
    {
        $this->testCreate(changePayload: [
            'provinsi_id' => null,
        ], status: 422)->assertJsonValidationErrors('provinsi_id');
    }

    public function test_validation_provinsi_id_exists()
    {
        $this->testCreate(changePayload: [
            'provinsi_id' => 'abc',
        ], status: 422)->assertJsonValidationErrors('provinsi_id');
    }

    public function test_validation_kabupaten_kota_id_required()
    {
        $this->testCreate(changePayload: [
            'kabupaten_kota_id' => null,
        ], status: 422)->assertJsonValidationErrors('kabupaten_kota_id');
    }

    public function test_validation_kabupaten_kota_id_exists()
    {
        $this->testCreate(changePayload: [
            'kabupaten_kota_id' => 'abc',
        ], status: 422)->assertJsonValidationErrors('kabupaten_kota_id');
    }

    public function test_validation_kecamatan_id_required()
    {
        $this->testCreate(changePayload: [
            'kecamatan_id' => null,
        ], status: 422)->assertJsonValidationErrors('kecamatan_id');
    }

    public function test_validation_kecamatan_id_exists()
    {
        $this->testCreate(changePayload: [
            'kecamatan_id' => 'abc',
        ], status: 422)->assertJsonValidationErrors('kecamatan_id');
    }

    public function test_validation_pendidikan_terakhir_required()
    {
        $this->testCreate(changePayload: [
            'pendidikan_terakhir' => null,
        ], status: 422)->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_validation_pendidikan_terakhir_min_3()
    {
        $this->testCreate(changePayload: [
            'pendidikan_terakhir' => 'Ja',
        ], status: 422)->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_validation_pendidikan_terakhir_max_100()
    {
        $this->testCreate(changePayload: [
            'pendidikan_terakhir' => str_repeat('a', 101),
        ], status: 422)->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_validation_kode_pos_required()
    {
        $this->testCreate(changePayload: [
            'kode_pos' => null,
        ], status: 422)->assertJsonValidationErrors('kode_pos');
    }

    public function test_validation_kode_pos_numeric()
    {
        $this->testCreate(changePayload: [
            'kode_pos' => 'abc',
        ], status: 422)->assertJsonValidationErrors('kode_pos');
    }

    public function test_validation_kode_pos_digits_5()
    {
        $this->testCreate(changePayload: [
            'kode_pos' => '1234',
        ], status: 422)->assertJsonValidationErrors('kode_pos');
    }

    public function test_validation_nomor_telepon_required()
    {
        $this->testCreate(changePayload: [
            'nomor_telepon' => null,
        ], status: 422)->assertJsonValidationErrors('nomor_telepon');
    }

    public function test_validation_nomor_telepon_numeric()
    {
        $this->testCreate(changePayload: [
            'nomor_telepon' => 'abc',
        ], status: 422)->assertJsonValidationErrors('nomor_telepon');
    }

    public function test_validation_nomor_telepon_digits_between_10_and_15()
    {
        $this->testCreate(changePayload: [
            'nomor_telepon' => '123456789',
        ], status: 422)->assertJsonValidationErrors('nomor_telepon');
    }

    public function test_validation_nomor_telepon_starts_with_0_or_62()
    {
        $this->testCreate(changePayload: [
            'nomor_telepon' => '123456789',
        ], status: 422)->assertJsonValidationErrors('nomor_telepon');
    }

    public function test_validation_nama_kantor_required()
    {
        $this->testCreate(changePayload: [
            'nama_kantor' => null,
        ], status: 422)->assertJsonValidationErrors('nama_kantor');
    }

    public function test_validation_nama_kantor_min_3()
    {
        $this->testCreate(changePayload: [
            'nama_kantor' => 'Ja',
        ], status: 422)->assertJsonValidationErrors('nama_kantor');
    }

    public function test_validation_nama_kantor_max_100()
    {
        $this->testCreate(changePayload: [
            'nama_kantor' => str_repeat('a', 101),
        ], status: 422)->assertJsonValidationErrors('nama_kantor');
    }

    public function test_validation_alamat_kantor_required()
    {
        $this->testCreate(changePayload: [
            'alamat_kantor' => null,
        ], status: 422)->assertJsonValidationErrors('alamat_kantor');
    }

    public function test_validation_alamat_kantor_min_3()
    {
        $this->testCreate(changePayload: [
            'alamat_kantor' => 'Ja',
        ], status: 422)->assertJsonValidationErrors('alamat_kantor');
    }

    public function test_validation_alamat_kantor_max_100()
    {
        $this->testCreate(changePayload: [
            'alamat_kantor' => str_repeat('a', 101),
        ], status: 422)->assertJsonValidationErrors('alamat_kantor');
    }

    public function test_validation_telepon_kantor_required()
    {
        $this->testCreate(changePayload: [
            'telepon_kantor' => null,
        ], status: 422)->assertJsonValidationErrors('telepon_kantor');
    }

    public function test_validation_telepon_kantor_numeric()
    {
        $this->testCreate(changePayload: [
            'telepon_kantor' => 'abc',
        ], status: 422)->assertJsonValidationErrors('telepon_kantor');
    }

    public function test_validation_telepon_kantor_digits_between_5_and_15()
    {
        $this->testCreate(changePayload: [
            'telepon_kantor' => '1234',
        ], status: 422)->assertJsonValidationErrors('telepon_kantor');
    }

    public function test_validation_jabatan_required()
    {
        $this->testCreate(changePayload: [
            'jabatan' => null,
        ], status: 422)->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_jabatan_min_3()
    {
        $this->testCreate(changePayload: [
            'jabatan' => 'Ja',
        ], status: 422)->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_jabatan_max_100()
    {
        $this->testCreate(changePayload: [
            'jabatan' => str_repeat('a', 101),
        ], status: 422)->assertJsonValidationErrors('jabatan');
    }

    public function test_validation_status_pekerjaan_required()
    {
        $this->testCreate(changePayload: [
            'status_pekerjaan' => null,
        ], status: 422)->assertJsonValidationErrors('status_pekerjaan');
    }

    public function test_validation_status_pekerjaan_is_valid()
    {
        $this->testCreate(changePayload: [
            'status_pekerjaan' => 'X',
        ], status: 422)->assertJsonValidationErrors('status_pekerjaan');
    }
}
