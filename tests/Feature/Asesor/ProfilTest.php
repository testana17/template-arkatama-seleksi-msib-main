<?php

namespace Tests\Feature\Asesor;

use App\Models\Akademik\ProgramStudi;
use App\Models\Asesor\Penilaian;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Asesor;
use App\Models\Rpl\AsesorPeserta;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\CreatesApplication;
use Tests\TestCase;

class ProfilTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    private $asesor;

    private $route;

    private $formulir;

    private $penilaian;

    public $defaultPayload;

    public $defaultPayloadPraktisi;

    public function setUp(): void
    {
        parent::setUp();

        $pembayaran = Pembayaran::factory()->create();
        $this->formulir = $pembayaran->register->formulir;

        $matakuliah = Matakuliah::first();

        $this->asesor = User::where('email', 'asesor@arkatama.test')->first();

        $matkul_asesor = MatakuliahAsesor::create(
            [
                'matkul_id' => $matakuliah->id,
                'asesor_id' => $this->asesor->asAsesorInstance->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        );

        AsesorPeserta::create(
            [
                'matkul_asesor_id' => $matkul_asesor->id,
                'formulir_id' => $this->formulir->id,
            ]
        );

        $this->actingAs($this->formulir->register->user);

        $this->penilaian = Penilaian::create([
            'status_kelulusan' => 'L',
            'rekomendasi' => 'Direkomendasikan',
            'nilai' => 'B',
            'matkul_id' => $matakuliah->id,
            'formulir_id' => $this->formulir->id,
            'tingkat_kemampuan' => 'Baik',
            'is_valid' => 1,
        ]);

        $this->penilaian->detail_penilaian()
            ->create([
                'status_kelulusan' => 'L',
                'keterangan' => 'Keterangan',
                'matkul_id' => $this->penilaian->matkul_id,
                'nilai_angka' => 80,
                'nilai_huruf' => $this->penilaian->nilai,
                'matkul_asesor_id' => $matkul_asesor->id,
            ], );

        $this->route = 'asesor.profil';

        $this->defaultPayload = [
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'nama_lengkap' => 'Updated Jane Doe',
            'tempat_lahir' => 'Updated Jakarta',
            'tanggal_lahir' => '1991-10-10',
            'jenis_kelamin' => 'P',
            'no_hp' => '080230067890',
            'email' => 'janedoe@mai.com',
            'alamat' => 'Jl. Jalan',
            'pendidikan_terakhir' => 'S1',
            'bidang_keahlian' => 'IT',
            'asosiasi_profesi_keanggotaan' => 'IT',
            'asosiasi_profesi_no_anggota' => '0993456',
            'akademik_pangkat' => 'Pengajar',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '000456789012345008',
            'akademik_nidn' => '0000567890',
            'akademik_nama_pt' => 'Universitas',
            'akademik_alamat_pt' => 'Jl. Universitas',
            'foto_profil' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
        ];

        $this->defaultPayloadPraktisi = [
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'nama_lengkap' => 'Updated James Doe',
            'tempat_lahir' => 'Updated Surabaya',
            'tanggal_lahir' => '1992-03-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '081231061190',
            'email' => 'updatedjamesdoe@mai.com',
            'alamat' => 'Jl. Jalan Surabaya',
            'pendidikan_terakhir' => 'S1',
            'bidang_keahlian' => 'IT',
            'asosiasi_profesi_keanggotaan' => 'IT',
            'asosiasi_profesi_no_anggota' => '1993456',
            'profesi_pekerjaan' => 'Programmer',
            'profesi_nama_instansi' => 'PT. Arkatama Multi Solusindo',
            'profesi_jabatan_instansi' => 'Senior Programmer',
            'profesi_alamat_instansi' => 'Perubahan Joyo Agung Greenland',
            'profesi_no_telp_instansi' => '081990061190',
            'profesi_bidan_keahlian' => 'IT',
            'foto_profil' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
        ];
    }

    public function test_must_authenticated_to_access_page()
    {

        $response = $this->get(route($this->route.'.index'));
        $response->assertStatus(403);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->actingAs($this->asesor)->get(route($this->route.'.index'));
        $response->assertStatus(200);
    }

    public function test_update_profile_asesor()
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->put(route($this->route.'.update', $model->id), $this->defaultPayload);
        $response->assertStatus(200);
    }

    public function test_update_profile_asesor_nama_lengkap_is_required(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['nama_lengkap' => ''])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_profile_asesor_nama_lengkap_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['nama_lengkap' => 123])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_profile_asesor_nama_lengkap_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['nama_lengkap' => str_repeat('a', 201)])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_profile_asesor_nama_lengkap_is_min_3(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['nama_lengkap' => 'aa'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_profile_asesor_nama_lengkap_is_alpha_spaces(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['nama_lengkap' => 'Jane Doe 123'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_profile_asesor_tempat_lahir_is_required(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['tempat_lahir' => ''])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_update_profile_asesor_tempat_lahir_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['tempat_lahir' => 123])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_update_profile_asesor_tempat_lahir_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['tempat_lahir' => str_repeat('a', 201)])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_update_profile_asesor_tanggal_lahir_is_required(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['tanggal_lahir' => ''])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_update_profile_asesor_tanggal_lahir_is_date(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['tanggal_lahir' => '123'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_update_profile_asesor_tanggal_lahir_is_before_or_equal_today(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['tanggal_lahir' => Carbon::now()->addDay()->format('Y-m-d')])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_update_profile_asesor_jenis_kelamin_is_required(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['jenis_kelamin' => ''])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_update_profile_asesor_jenis_kelamin_is_in_L_or_P(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['jenis_kelamin' => 'X'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_update_profile_asesor_no_hp_is_required(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['no_hp' => ''])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_profile_asesor_no_hp_is_numeric(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['no_hp' => 'abc'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_profile_asesor_no_hp_is_starts_with_0_or_62(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['no_hp' => '123'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_profile_asesor_no_hp_is_digits_between_10_and_15(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['no_hp' => '0812345678901231'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_profile_asesor_no_fax_is_numeric(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['no_fax' => 'abc']));
        $response->assertStatus(422)->assertJsonValidationErrors('no_fax');
    }

    public function test_update_profile_asesor_no_fax_is_starts_with_0_or_62(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['no_fax' => '123']));
        $response->assertStatus(422)->assertJsonValidationErrors('no_fax');
    }

    public function test_update_profile_asesor_no_fax_is_digits_between_10_and_15(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['no_fax' => '0812345678901231']));
        $response->assertStatus(422)->assertJsonValidationErrors('no_fax');
    }

    public function test_update_profile_asesor_email_is_required(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['email' => '']));
        $response->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_update_profile_asesor_email_is_email(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['email' => 'abc']));
        $response->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_update_profile_asesor_alamat_is_required(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['alamat' => '']));
        $response->assertStatus(422)->assertJsonValidationErrors('alamat');
    }

    public function test_update_profile_asesor_alamat_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['alamat' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('alamat');
    }

    public function test_update_profile_asesor_provinsi_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['provinsi' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('provinsi');
    }

    public function test_update_profile_asesor_provinsi_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['provinsi' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('provinsi');
    }

    public function test_update_profile_asesor_kabupaten_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['kabupaten' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('kabupaten');
    }

    public function test_update_profile_asesor_kabupaten_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['kabupaten' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('kabupaten');
    }

    public function test_update_profile_asesor_kecamatan_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['kecamatan' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('kecamatan');
    }

    public function test_update_profile_asesor_kecamatan_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['kecamatan' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('kecamatan');
    }

    public function test_update_profile_asesor_kodepos_is_numeric(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['kodepos' => 'abc']));
        $response->assertStatus(422)->assertJsonValidationErrors('kodepos');
    }

    public function test_update_profile_asesor_pendidikan_terakhir_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['pendidikan_terakhir' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_update_profile_asesor_pendidikan_terakhir_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['pendidikan_terakhir' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_update_profile_asesor_bidang_keahlian_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['bidang_keahlian' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_update_profile_asesor_bidang_keahlian_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['bidang_keahlian' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_update_profile_asesor_asosiasi_profesi_keanggotaan_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_update_profile_asesor_asosiasi_profesi_keanggotaan_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_update_profile_asesor_asosiasi_profesi_keanggotaan_is_alpha_spaces(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(
            route($this->route.'.update', $model->id),
            array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => 'IT 123'])
        )->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_update_profile_asesor_asosiasi_profesi_no_anggota_is_numeric(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['asosiasi_profesi_no_anggota' => 'abc']));
        $response->assertStatus(422)->assertJsonValidationErrors('asosiasi_profesi_no_anggota');
    }

    public function test_update_profile_asesor_akademik_pangkat_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_pangkat' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_pangkat');
    }

    public function test_update_profile_asesor_akademik_pangkat_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_pangkat' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_pangkat');
    }

    public function test_update_profile_asesor_akademik_jabatan_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_jabatan' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_jabatan');
    }

    public function test_update_profile_asesor_akademik_jabatan_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_jabatan' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_jabatan');
    }

    public function test_update_profile_asesor_akademik_nip_is_numeric(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_nip' => 'abc']));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_nip');
    }

    public function test_update_profile_asesor_akademik_nidn_is_numeric(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_nidn' => 'abc']));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_nidn');
    }

    public function test_update_profile_asesor_akademik_nama_pt_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_nama_pt' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_nama_pt');
    }

    public function test_update_profile_asesor_akademik_nama_pt_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_nama_pt' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_nama_pt');
    }

    public function test_update_profile_asesor_akademik_alamat_pt_is_string(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_alamat_pt' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_alamat_pt');
    }

    public function test_update_profile_asesor_akademik_alamat_pt_is_max_200(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();
        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['akademik_alamat_pt' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('akademik_alamat_pt');
    }

    public function test_update_profile_asesor_profesi_pekerjaan_is_string(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_pekerjaan' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_pekerjaan');
    }

    public function test_update_profile_asesor_profesi_pekerjaan_is_max_200(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_pekerjaan' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_pekerjaan');
    }

    public function test_update_profile_asesor_profesi_instansi_is_string(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_nama_instansi' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_nama_instansi');
    }

    public function test_update_profile_asesor_profesi_instansi_is_max_200(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_nama_instansi' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_nama_instansi');
    }

    public function test_update_profile_asesor_profesi_jabatan_instansi_is_string(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_jabatan_instansi' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_jabatan_instansi');
    }

    public function test_update_profile_asesor_profesi_jabatan_instansi_is_max_200(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_jabatan_instansi' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_jabatan_instansi');
    }

    public function test_update_profile_asesor_profesi_alamat_instansi_is_string(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_alamat_instansi' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_alamat_instansi');
    }

    public function test_update_profile_asesor_profesi_alamat_instansi_is_max_200(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_alamat_instansi' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_alamat_instansi');
    }

    public function test_update_profile_asesor_profesi_bidang_keahlian_is_string(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_bidang_keahlian' => 123]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_bidang_keahlian');
    }

    public function test_update_profile_asesor_profesi_bidang_keahlian_is_max_200(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_bidang_keahlian' => str_repeat('a', 201)]));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_bidang_keahlian');
    }

    public function test_update_profile_asesor_profesi_telepon_instansi_is_numeric(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_telepon_instansi' => 'abc']));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_telepon_instansi');
    }

    public function test_update_profile_asesor_profesi_telepon_instansi_is_starts_with_0_or_62(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_telepon_instansi' => '123']));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_telepon_instansi');
    }

    public function test_update_profile_asesor_profesi_telepon_instansi_is_digits_between_10_and_15(): void
    {
        $asesor = User::where('email', 'asesor3@arkatama.test')->first();
        $this->actingAs($asesor);
        $model = Asesor::where('user_id', $asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayloadPraktisi, ['profesi_telepon_instansi' => '0812345671890123']));
        $response->assertStatus(422)->assertJsonValidationErrors('profesi_telepon_instansi');
    }

    public function test_update_profile_asesor_foto_profil_is_image(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['foto_profil' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf')]));
        $response->assertStatus(422)->assertJsonValidationErrors('foto_profil');
    }

    public function test_update_profile_asesor_foto_profil_is_max_2048_kb(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['foto_profil' => UploadedFile::fake()->create('image.jpg', 3000, 'image/jpeg')]));
        $response->assertStatus(422)->assertJsonValidationErrors('foto_profil');
    }

    public function test_update_profile_asesor_foto_profil_is_jpg_jpeg_png(): void
    {
        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();

        $response = $this->putJson(route($this->route.'.update', $model->id), array_merge($this->defaultPayload, ['foto_profil' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf')]));
        $response->assertStatus(422)->assertJsonValidationErrors('foto_profil');
    }

    public function test_update_password(): void
    {

        $this->actingAs($this->asesor);
        $model = Asesor::where('user_id', $this->asesor->id)->first();

        $response = $this->postJson(route($this->route.'.update-password', $model->id), [
            'password' => 'password',
            'confirm_password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}
