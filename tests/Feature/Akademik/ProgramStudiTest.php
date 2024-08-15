<?php

namespace Tests\Feature\Akademik;

use App\Models\Akademik\Fakultas;
use App\Models\Akademik\ProgramStudi;
use App\Models\User;
use Tests\SimpleTest;

class ProgramStudiTest extends SimpleTest
{
    protected $table = 'ref_prodi';

    protected $model = ProgramStudi::class;

    protected $route = 'akademik.program-studi.';

    protected $routeParam = 'prodi';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $fk = Fakultas::first();
        $this->payload = [
            'jenjang_pendidikan_id' => 1,
            'fakultas_id' => $fk->id,
            'kode_dikti' => rand(10000, 99999),
            'kode' => rand(10000, 99999),
            'nama_prodi' => 'Prodi Test',
            'singkatan' => 'PT',
        ];
    }

    public function test_must_authenticated_to_access_page(): void
    {
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(302);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route($this->route.'index'));
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

    public function test_can_create_program_studi_with_valid_payload(): void
    {
        $this->testCreate();
    }

    public function test_insert_validation_jenjang_pendidikan_required(): void
    {
        $this->testCreate([
            'jenjang_pendidikan_id' => null,
        ], 422)->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_insert_validation_fakultas_id_required(): void
    {
        $this->testCreate([
            'fakultas_id' => null,
        ], 422)->assertJsonValidationErrors('fakultas_id');
    }

    public function test_insert_validation_kode_dikti_can_be_null(): void
    {
        $this->testCreate([
            'kode_dikti' => null,
        ])->assertJsonMissingValidationErrors('kode_dikti');
    }

    public function test_insert_validation_kode_required(): void
    {
        $this->testCreate([
            'kode' => null,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_nama_prodi_required(): void
    {
        $this->testCreate([
            'nama_prodi' => null,
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_insert_validation_singkatan_required(): void
    {
        $this->testCreate([
            'singkatan' => null,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_cannot_insert_if_jenjang_pendidikan_not_exist_on_database(): void
    {
        $this->testCreate([
            'jenjang_pendidikan_id' => 999,
        ], 422)->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_insert_validation_cannot_insert_if_fakultas_id_not_exist_on_database(): void
    {
        $this->testCreate([
            'fakultas_id' => 999,
        ], 422)->assertJsonValidationErrors('fakultas_id');
    }

    public function test_insert_validation_kode_dikti_must_numeric(): void
    {
        $this->testCreate([
            'kode_dikti' => 'not-numeric',
        ], 422)->assertJsonValidationErrors('kode_dikti');
    }

    public function test_insert_validation_kode_dikti_max_digits_is_10(): void
    {
        $this->testCreate([
            'kode_dikti' => 12345678901,
        ], 422)->assertJsonValidationErrors('kode_dikti');
    }

    public function test_insert_validation_kode_dikti_must_unique()
    {
        $this->testCreate([
            'kode_dikti' => ProgramStudi::first()->kode_dikti,
        ], 422)->assertJsonValidationErrors('kode_dikti');
    }

    public function test_insert_validation_kode_must_numeric(): void
    {
        $this->testCreate([
            'kode' => 'not-numeric',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_must_contains_5_digits(): void
    {
        $this->testCreate([
            'kode' => 1234,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_must_unique()
    {
        $this->testCreate([
            'kode' => ProgramStudi::first()->kode,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_nama_prodi_min_3(): void
    {
        $this->testCreate([
            'nama_prodi' => 'aa',
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_insert_validation_nama_prodi_max_100(): void
    {
        $this->testCreate([
            'nama_prodi' => str_repeat('a', 101),
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_insert_validation_nama_prodi_cannot_contains_any_numbers(): void
    {
        $this->testCreate([
            'nama_prodi' => 123,
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_insert_validation_nama_prodi_cannot_contains_any_symbols(): void
    {
        $this->testCreate([
            'nama_prodi' => 'a@b',
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_insert_validation_singkatan_min_1(): void
    {
        $this->testCreate([
            'singkatan' => '',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_max_10(): void
    {
        $this->testCreate([
            'singkatan' => str_repeat('a', 11),
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_cannot_contains_any_symbols(): void
    {
        $this->testCreate([
            'singkatan' => 'a@b',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_cannot_contains_any_numbers(): void
    {
        $this->testCreate([
            'singkatan' => 123,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_must_unique()
    {
        $this->testCreate([
            'singkatan' => ProgramStudi::first()->singkatan,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_can_update_program_studi_with_valid_payload(): void
    {
        $this->testUpdate();
    }

    public function test_update_validation_jenjang_pendidikan_required(): void
    {
        $this->testUpdate([
            'jenjang_pendidikan_id' => null,
        ], 422)->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_update_validation_fakultas_id_required(): void
    {
        $this->testUpdate([
            'fakultas_id' => null,
        ], 422)->assertJsonValidationErrors('fakultas_id');
    }

    public function test_update_validation_kode_dikti_can_be_null(): void
    {
        $this->testUpdate([
            'kode_dikti' => null,
        ])->assertJsonMissingValidationErrors('kode_dikti');
    }

    public function test_update_validation_kode_required(): void
    {
        $this->testUpdate([
            'kode' => null,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_nama_prodi_required(): void
    {
        $this->testUpdate([
            'nama_prodi' => null,
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_update_validation_singkatan_required(): void
    {
        $this->testUpdate([
            'singkatan' => null,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_cannot_update_if_jenjang_pendidikan_not_exist_on_database(): void
    {
        $this->testUpdate([
            'jenjang_pendidikan_id' => 999,
        ], 422)->assertJsonValidationErrors('jenjang_pendidikan_id');
    }

    public function test_update_validation_cannot_update_if_fakultas_id_not_exist_on_database(): void
    {
        $this->testUpdate([
            'fakultas_id' => 999,
        ], 422)->assertJsonValidationErrors('fakultas_id');
    }

    public function test_update_validation_kode_dikti_must_numeric(): void
    {
        $this->testUpdate([
            'kode_dikti' => 'not-numeric',
        ], 422)->assertJsonValidationErrors('kode_dikti');
    }

    public function test_update_validation_kode_dikti_max_digits_is_10(): void
    {
        $this->testUpdate([
            'kode_dikti' => 12345678901,
        ], 422)->assertJsonValidationErrors('kode_dikti');
    }

    public function test_update_validation_kode_must_numeric(): void
    {
        $this->testUpdate([
            'kode' => 'not-numeric',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_kode_must_contains_5_digits(): void
    {
        $this->testUpdate([
            'kode' => 1234,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_nama_prodi_min_3(): void
    {
        $this->testUpdate([
            'nama_prodi' => 'aa',
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_update_validation_nama_prodi_max_100(): void
    {
        $this->testUpdate([
            'nama_prodi' => str_repeat('a', 101),
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_update_validation_nama_prodi_cannot_contains_any_numbers(): void
    {
        $this->testUpdate([
            'nama_prodi' => 123,
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_update_validation_nama_prodi_cannot_contains_any_symbols(): void
    {
        $this->testUpdate([
            'nama_prodi' => 'a@b',
        ], 422)->assertJsonValidationErrors('nama_prodi');
    }

    public function test_update_validation_singkatan_min_1(): void
    {
        $this->testUpdate([
            'singkatan' => '',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_singkatan_max_10(): void
    {
        $this->testUpdate([
            'singkatan' => str_repeat('a', 11),
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_singkatan_cannot_contains_any_symbols(): void
    {
        $this->testUpdate([
            'singkatan' => 'a@b',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_singkatan_cannot_contains_any_numbers(): void
    {
        $this->testUpdate([
            'singkatan' => 123,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_can_delete_program_studi(): void
    {
        $this->testDelete();
    }

    public function test_can_restore_program_studi(): void
    {
        $this->testRestore();
    }
}
