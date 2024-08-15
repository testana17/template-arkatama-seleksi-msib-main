<?php

namespace Tests\Feature\RPL;

use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\Asesor;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class AsesorAkademisiTest extends CRUDTestCase
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
        $this->defaultPayload = [
            'jenis_asesor' => 'akademisi',
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '081230067890',
            'email' => 'jhondoe@mai.com',
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
        ];

        $this->route = 'rpl.asesor.akademisi.';
        $this->table = 'asesor';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('rpl.asesor.akademisi');
        $this->setBaseModel(Asesor::class);
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

    public function test_datatable_entry_histories_must_be_returned(): void
    {
        $this->testShowDatatable(route: $this->route.'histori');
    }

    public function test_can_create_asesor_akademisi_with_valid_payload()
    {
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $this->defaultPayload);
        $response->assertStatus(201);
        $response->assertJsonStructure(['data']);

        return $response->json();
    }

    public function test_create_validation_asesor_akademisi_jenis_asesor_required()
    {
        $payload = array_merge($this->defaultPayload, ['jenis_asesor' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_asesor');
    }

    public function test_create_validation_asesor_akademisi_jenis_asesor_string()
    {
        $payload = array_merge($this->defaultPayload, ['jenis_asesor' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_asesor');
    }

    public function test_create_validation_asesor_akademisi_jenis_asesor_in()
    {
        $payload = array_merge($this->defaultPayload, ['jenis_asesor' => 'invalid']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_asesor');
    }

    public function test_create_validation_asesor_akademisi_prodi_id_required()
    {
        $payload = array_merge($this->defaultPayload, ['prodi_id' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_asesor_akademisi_prodi_id_exists()
    {
        $payload = array_merge($this->defaultPayload, ['prodi_id' => 999]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_asesor_akademisi_nama_lengkap_required()
    {
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_create_validation_asesor_akademisi_nama_lengkap_max()
    {
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_create_validation_asesor_akademisi_nama_lengkap_min()
    {
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => 'aa']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_create_validation_asesor_akademisi_nama_lengkap_alpha_spaces()
    {
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => 'John Doe 123']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_create_validation_asesor_akademisi_tempat_lahir_required()
    {
        $payload = array_merge($this->defaultPayload, ['tempat_lahir' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_create_validation_asesor_akademisi_tempat_lahir_string()
    {
        $payload = array_merge($this->defaultPayload, ['tempat_lahir' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_create_validation_asesor_akademisi_tempat_lahir_max()
    {
        $payload = array_merge($this->defaultPayload, ['tempat_lahir' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_create_validation_asesor_akademisi_tanggal_lahir_required()
    {
        $payload = array_merge($this->defaultPayload, ['tanggal_lahir' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_create_validation_asesor_akademisi_tanggal_lahir_date()
    {
        $payload = array_merge($this->defaultPayload, ['tanggal_lahir' => 'invalid-date']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_create_validation_asesor_akademisi_tanggal_lahir_before_or_equal()
    {
        $payload = array_merge($this->defaultPayload, ['tanggal_lahir' => '2099-01-01']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_create_validation_asesor_akademisi_jenis_kelamin_required()
    {
        $payload = array_merge($this->defaultPayload, ['jenis_kelamin' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_create_validation_asesor_akademisi_jenis_kelamin_string()
    {
        $payload = array_merge($this->defaultPayload, ['jenis_kelamin' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_create_validation_asesor_akademisi_jenis_kelamin_in()
    {
        $payload = array_merge($this->defaultPayload, ['jenis_kelamin' => 'invalid']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_create_validation_asesor_akademisi_no_hp_required()
    {
        $payload = array_merge($this->defaultPayload, ['no_hp' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_create_validation_asesor_akademisi_no_hp_numeric()
    {
        $payload = array_merge($this->defaultPayload, ['no_hp' => 'invalid-no-hp']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_create_validation_asesor_akademisi_no_hp_starts_with()
    {
        $payload = array_merge($this->defaultPayload, ['no_hp' => '81230067890']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_create_validation_asesor_akademisi_no_hp_digits_between()
    {
        $payload = array_merge($this->defaultPayload, ['no_hp' => '0812300678901234567890']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_create_validation_asesor_akademisi_email_required()
    {
        $payload = array_merge($this->defaultPayload, ['email' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_create_validation_asesor_akademisi_email_email()
    {
        $payload = array_merge($this->defaultPayload, ['email' => 'invalid-email']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_create_validation_asesor_akademisi_email_unique()
    {
        $this->test_can_create_asesor_akademisi_with_valid_payload();
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $this->defaultPayload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_create_validation_asesor_akademisi_alamat_required()
    {
        $payload = array_merge($this->defaultPayload, ['alamat' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('alamat');
    }

    public function test_create_validation_asesor_akademisi_alamat_string()
    {
        $payload = array_merge($this->defaultPayload, ['alamat' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('alamat');
    }

    public function test_create_validation_asesor_akademisi_pendidikan_terakhir_required()
    {
        $payload = array_merge($this->defaultPayload, ['pendidikan_terakhir' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_create_validation_asesor_akademisi_pendidikan_terakhir_string()
    {
        $payload = array_merge($this->defaultPayload, ['pendidikan_terakhir' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_create_validation_asesor_akademisi_pendidikan_terakhir_max()
    {
        $payload = array_merge($this->defaultPayload, ['pendidikan_terakhir' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_create_validation_asesor_akademisi_bidang_keahlian_required()
    {
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_create_validation_asesor_akademisi_bidang_keahlian_string()
    {
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_create_validation_asesor_akademisi_bidang_keahlian_max()
    {
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_create_validation_asesor_akademisi_bidang_keahlian_alpha_spaces()
    {
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => 'IT 123']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_create_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_required()
    {
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_create_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_string()
    {
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_create_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_max()
    {
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_create_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_alpha_spaces()
    {
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => 'Asosiasi 123']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_create_validation_asesor_akademisi_asosiasi_profesi_no_anggota_required()
    {
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_no_anggota' => '']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_no_anggota');
    }

    public function test_create_validation_asesor_akademisi_asosiasi_profesi_no_anggota_string()
    {
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_no_anggota' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_no_anggota');
    }

    public function test_create_validation_asesor_akademisi_asosiasi_profesi_no_anggota_max()
    {
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_no_anggota' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_no_anggota');
    }

    public function test_create_validation_asesor_akademisi_akademik_pangkat_string()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_pangkat' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_pangkat_max()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_pangkat' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_pangkat_alpha_spaces()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_pangkat' => 'Pangkat 123']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_jabatan_string()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_jabatan' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_jabatan_max()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_jabatan' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_jabatan_alpha_spaces()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_jabatan' => 'Jabatan 123']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_nip_digits()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_nip' => 'invalid-nip']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_nidn_digits()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_nidn' => 'invalid-nidn']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_nama_pt_string()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_nama_pt' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_nama_pt_max()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_nama_pt' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_alamat_pt_string()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_alamat_pt' => 123]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_akademik_alamat_pt_max()
    {
        $payload = array_merge($this->defaultPayload, ['akademik_alamat_pt' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_can_update_asesor_akademisi_with_valid_payload()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $updatedPayload = array_merge($this->defaultPayload, ['nama_lengkap' => 'Jane Doe']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $updatedPayload);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);

        return $response->json();
    }

    public function test_update_validation_asesor_akademisi_jenis_asesor_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['jenis_asesor' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_asesor');
    }

    public function test_update_validation_asesor_akademisi_jenis_asesor_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['jenis_asesor' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_asesor');
    }

    public function test_update_validation_asesor_akademisi_jenis_asesor_in()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['jenis_asesor' => 'invalid']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_asesor');
    }

    public function test_update_validation_asesor_akademisi_prodi_id_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['prodi_id' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_asesor_akademisi_prodi_id_exists()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['prodi_id' => 999]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_asesor_akademisi_nama_lengkap_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_validation_asesor_akademisi_nama_lengkap_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_validation_asesor_akademisi_nama_lengkap_min()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => 'aa']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_validation_asesor_akademisi_nama_lengkap_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_validation_asesor_akademisi_nama_lengkap_alpha_spaces()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['nama_lengkap' => 'John Doe 123']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_lengkap');
    }

    public function test_update_validation_asesor_akademisi_tempat_lahir_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['tempat_lahir' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_update_validation_asesor_akademisi_tempat_lahir_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['tempat_lahir' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_update_validation_asesor_akademisi_tempat_lahir_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['tempat_lahir' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tempat_lahir');
    }

    public function test_update_validation_asesor_akademisi_tanggal_lahir_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['tanggal_lahir' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_update_validation_asesor_akademisi_tanggal_lahir_date()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['tanggal_lahir' => 'invalid-date']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_update_validation_asesor_akademisi_tanggal_lahir_before_or_equal()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['tanggal_lahir' => '2099-01-01']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_lahir');
    }

    public function test_update_validation_asesor_akademisi_jenis_kelamin_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['jenis_kelamin' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_update_validation_asesor_akademisi_jenis_kelamin_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['jenis_kelamin' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_update_validation_asesor_akademisi_jenis_kelamin_in()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['jenis_kelamin' => 'invalid']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('jenis_kelamin');
    }

    public function test_update_validation_asesor_akademisi_no_hp_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['no_hp' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_validation_asesor_akademisi_no_hp_numeric()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['no_hp' => 'invalid-no-hp']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_validation_asesor_akademisi_no_hp_starts_with()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['no_hp' => '81230067890']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_validation_asesor_akademisi_no_hp_digits_between()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['no_hp' => '0812300678901234567890']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_validation_asesor_akademisi_email_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['email' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_update_validation_asesor_akademisi_email_email()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['email' => 'invalid-email']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_update_validation_asesor_akademisi_alamat_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['alamat' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('alamat');
    }

    public function test_update_validation_asesor_akademisi_alamat_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['alamat' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('alamat');
    }

    public function test_update_validation_asesor_akademisi_pendidikan_terakhir_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['pendidikan_terakhir' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_update_validation_asesor_akademisi_pendidikan_terakhir_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['pendidikan_terakhir' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_update_validation_asesor_akademisi_pendidikan_terakhir_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['pendidikan_terakhir' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('pendidikan_terakhir');
    }

    public function test_update_validation_asesor_akademisi_bidang_keahlian_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_update_validation_asesor_akademisi_bidang_keahlian_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_update_validation_asesor_akademisi_bidang_keahlian_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_update_validation_asesor_akademisi_bidang_keahlian_alpha_spaces()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['bidang_keahlian' => 'IT 123']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('bidang_keahlian');
    }

    public function test_update_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_update_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_update_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_update_validation_asesor_akademisi_asosiasi_profesi_keanggotaan_alpha_spaces()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_keanggotaan' => 'Asosiasi 123']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_keanggotaan');
    }

    public function test_update_validation_asesor_akademisi_asosiasi_profesi_no_anggota_required()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_no_anggota' => '']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_no_anggota');
    }

    public function test_update_validation_asesor_akademisi_asosiasi_profesi_no_anggota_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_no_anggota' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_no_anggota');
    }

    public function test_update_validation_asesor_akademisi_asosiasi_profesi_no_anggota_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['asosiasi_profesi_no_anggota' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asosiasi_profesi_no_anggota');
    }

    public function test_update_validation_asesor_akademisi_akademik_pangkat_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_pangkat' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_pangkat_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_pangkat' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_pangkat_alpha_spaces()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_pangkat' => 'Pangkat 123']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_jabatan_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_jabatan' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_jabatan_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_jabatan' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_jabatan_alpha_spaces()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_jabatan' => 'Jabatan 123']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_nip_digits()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_nip' => 'invalid-nip']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_nidn_digits()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_nidn' => 'invalid-nidn']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_nama_pt_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_nama_pt' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_nama_pt_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_nama_pt' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_alamat_pt_string()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_alamat_pt' => 123]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_asesor_akademisi_akademik_alamat_pt_max()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['akademik_alamat_pt' => str_repeat('a', 201)]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_asesor_akademisi_no_hp_unique()
    {
        $asesor = Asesor::where('nama_lengkap', 'Ahmad Nur Ramadhani')->first();
        $payload = array_merge($this->defaultPayload, ['no_hp' => $asesor->no_hp]);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('no_hp');
    }

    public function test_update_validation_asesor_akademisi_email_unique()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $payload = array_merge($this->defaultPayload, ['email' => $this->adminUser->email]);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $asesor['data']['id']), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    //error 500 karena restrict on delete
    public function test_can_delete_asesor_akademisi()
    {
        $asesor = $this->test_can_create_asesor_akademisi_with_valid_payload();
        $response = $this->actingAs($this->adminUser)->deleteJson(route($this->route.'destroy', $asesor['data']['id']));
        $response->assertStatus(200);

        return $asesor;
    }

    public function test_can_restore_asesor_akademisi()
    {
        $asesor = $this->test_can_delete_asesor_akademisi();
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'restore', ['akademisi' => $asesor['data']['id']]));
        $response->assertStatus(200);

        return $asesor;
    }
}
