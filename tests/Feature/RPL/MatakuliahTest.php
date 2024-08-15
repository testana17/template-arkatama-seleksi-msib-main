<?php

namespace Tests\Feature\RPL;

use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\Matakuliah;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class MatakuliahTest extends CRUDTestCase
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
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
            'kode_mk' => 'PD001',
            'nama_mk' => 'Pemrograman Desktop',
            'sks_tatap_muka' => 2,
            'sks_praktek' => 2,
            'sks_praktek_lapangan' => 0,
            'sks_simulasi' => 0,
            'sks_praktikum' => 0,
        ];

        $this->route = 'rpl.mata-kuliah.';
        $this->table = 'matakuliah';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('rpl.mata-kuliah');
        $this->setBaseModel(Matakuliah::class);
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

    public function test_can_create_matakuliah(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_matakuliah()
    {
        $model = $this->test_can_create_matakuliah();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'kode_mk' => 'PDD001',
                'nama_mk' => 'Pemrograman Desktop Dasar',
                'sks_tatap_muka' => 3,
                'sks_praktek' => 2,
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_can_delete_matakuliah()
    {
        $model = $this->test_can_create_matakuliah();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_matakuliah()
    {
        $model = $this->test_can_delete_matakuliah();
        $response = $this->testRestore(model: $model);
    }

    public function test_can_create_matakuliah_nama_mk_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['nama_mk']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_can_create_matakuliah_nama_mk_min_3_characters()
    {
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = 'ab';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_can_create_matakuliah_nama_mk_max_100_characters()
    {
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = str_repeat('a', 101);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_can_create_matakuliah_nama_mk_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = 'Pemrograman Test 123.pdf';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_can_create_matakuliah_nama_mk_cannot_contains_only_numbers()
    {
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = '123';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_can_create_matakuliah_nama_mk_cannot_contains_only_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = '!!!';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_update_validation_nama_mk_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = null;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_update_validation_nama_mk_min_3_characters()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = 'ab';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_update_validation_nama_mk_max_100_characters()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = str_repeat('a', 101);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_update_validation_nama_mk_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = 'Pemrograman Test 123.pdf';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_nama_mk_cannot_contains_only_numbers()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = '123';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_update_validation_nama_mk_cannot_contains_only_symbols()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['nama_mk'] = '!!!';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nama_mk');
    }

    public function test_create_validation_sks_tatap_muka_is_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['sks_tatap_muka']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_tatap_muka');
    }

    public function test_create_validation_sks_tatap_muka_must_be_integer()
    {
        $payload = $this->defaultPayload;
        $payload['sks_tatap_muka'] = 'abc';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_tatap_muka');
    }

    public function test_update_validation_sks_tatap_muka_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        unset($payload['sks_tatap_muka']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_tatap_muka');
    }

    public function test_update_validation_sks_tatap_muka_must_be_integer()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['sks_tatap_muka'] = 'abc';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_tatap_muka');
    }

    public function test_create_validation_sks_praktek_is_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['sks_praktek']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek');
    }

    public function test_create_validation_sks_praktek_must_be_integer()
    {
        $payload = $this->defaultPayload;
        $payload['sks_praktek'] = 'abc';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek');
    }

    public function test_update_validation_sks_praktek_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        unset($payload['sks_praktek']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek');
    }

    public function test_update_validation_sks_praktek_must_be_integer()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['sks_praktek'] = 'abc';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek');
    }

    public function test_create_validation_sks_praktek_lapangan_is_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['sks_praktek_lapangan']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek_lapangan');
    }

    public function test_create_validation_sks_praktek_lapangan_must_be_integer()
    {
        $payload = $this->defaultPayload;
        $payload['sks_praktek_lapangan'] = 'abc';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek_lapangan');
    }

    public function test_update_validation_sks_praktek_lapangan_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        unset($payload['sks_praktek_lapangan']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek_lapangan');
    }

    public function test_update_validation_sks_praktek_lapangan_must_be_integer()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['sks_praktek_lapangan'] = 'abc';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktek_lapangan');
    }

    public function test_create_validation_sks_simulasi_is_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['sks_simulasi']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_simulasi');
    }

    public function test_create_validation_sks_simulasi_must_be_integer()
    {
        $payload = $this->defaultPayload;
        $payload['sks_simulasi'] = 'abc';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_simulasi');
    }

    public function test_update_validation_sks_simulasi_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        unset($payload['sks_simulasi']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_simulasi');
    }

    public function test_update_validation_sks_simulasi_must_be_integer()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['sks_simulasi'] = 'abc';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_simulasi');
    }

    public function test_create_validation_sks_praktikum_is_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['sks_praktikum']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktikum');
    }

    public function test_create_validation_sks_praktikum_must_be_integer()
    {
        $payload = $this->defaultPayload;
        $payload['sks_praktikum'] = 'abc';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktikum');
    }

    public function test_update_validation_sks_praktikum_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        unset($payload['sks_praktikum']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktikum');
    }

    public function test_update_validation_sks_praktikum_must_be_integer()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['sks_praktikum'] = 'abc';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sks_praktikum');
    }

    public function test_create_validation_prodi_id_is_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['prodi_id']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_prodi_id_must_exists()
    {
        $payload = $this->defaultPayload;
        $payload['prodi_id'] = 999;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_prodi_id_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        unset($payload['prodi_id']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_prodi_id_must_exists()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['prodi_id'] = 999;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_kode_mk_is_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['kode_mk']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }

    public function test_create_validation_kode_mk_must_be_unique()
    {
        $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }

    public function test_update_validation_kode_mk_is_required()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        unset($payload['kode_mk']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }

    public function test_update_validation_kode_mk_must_be_unique()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['kode_mk'] = 'PD001';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }

    public function test_create_validation_kode_mk_must_be_string()
    {
        $payload = $this->defaultPayload;
        $payload['kode_mk'] = 123;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }

    public function test_update_validation_kode_mk_must_be_string()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['kode_mk'] = 123;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }

    public function test_create_validation_kode_mk_max_10_characters()
    {
        $payload = $this->defaultPayload;
        $payload['kode_mk'] = str_repeat('a', 11);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }

    public function test_update_validation_kode_mk_max_10_characters()
    {
        $model = $this->test_can_create_matakuliah();
        $payload = $this->defaultPayload;
        $payload['kode_mk'] = str_repeat('a', 11);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kode_mk');
    }
}
