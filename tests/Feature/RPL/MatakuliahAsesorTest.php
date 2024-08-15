<?php

namespace Tests\Feature\RPL;

use App\Models\Rpl\Asesor;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class MatakuliahAsesorTest extends CRUDTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    private $adminUser;

    private $route;

    private $table;

    private $model;

    public $defaultPayload;

    public function setUp(): void
    {
        parent::setUp();
        $adminUser = User::where('email', 'admin@arkatama.test')->first();
        $this->adminUser = $adminUser;
        $this->defaultPayload = [
            'asesor_id' => Asesor::first()->id,
            'matkul_id' => Matakuliah::first()->id,
        ];
        $this->route = 'pengaturan-rpl.matakuliah-asesor.';
        $this->table = 'matakuliah_asesor';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('pengaturan-rpl.matakuliah-asesor');
        $this->setBaseModel(MatakuliahAsesor::class);
    }

    public function test_must_authenticated_to_access_page()
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

    public function test_can_create_matakuliah_asesor(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_matakuliah_asesor()
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $updatePayload = array_merge($this->defaultPayload, [
            'matkul_id' => Matakuliah::offset(1)->first()->id,
        ]);
        $response = $this->testUpdate(model: $model, attributes: $updatePayload);
    }

    public function test_can_delete_matakuliah_asesor()
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_matakuliah_asesor()
    {
        $model = $this->test_can_delete_matakuliah_asesor();
        $response = $this->testRestore(model: $model);
    }

    public function test_create_validation_asesor_id_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['asesor_id']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asesor_id');
    }

    public function test_create_validation_asesor_id_exists(): void
    {
        $payload = $this->defaultPayload;
        $payload['asesor_id'] = 999;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asesor_id');
    }

    public function test_create_validation_asesor_id_unique(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $payload = $this->defaultPayload;
        $payload['matkul_id'] = $model->matkul_id;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('asesor_id');
    }

    public function test_create_validation_matkul_id_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['matkul_id']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('matkul_id');
    }

    public function test_create_validation_matkul_id_exists(): void
    {
        $payload = $this->defaultPayload;
        $payload['matkul_id'] = 999;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('matkul_id');
    }

    public function test_create_validation_matkul_id_unique(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $payload = $this->defaultPayload;
        $payload['asesor_id'] = $model->asesor_id;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('matkul_id');
    }

    public function test_update_validation_asesor_id_required(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $payload = $this->defaultPayload;
        unset($payload['asesor_id']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('asesor_id');
    }

    public function test_update_validation_asesor_id_exists(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $payload = $this->defaultPayload;
        $payload['asesor_id'] = 999;

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('asesor_id');
    }

    public function test_update_validation_asesor_id_unique(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $model2 = MatakuliahAsesor::where('id', '!=', $model->id)->first();
        $payload = $this->defaultPayload;
        $payload['matkul_id'] = $model->matkul_id;

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model2->id), $payload)
            ->assertJsonValidationErrors('asesor_id');
    }

    public function test_update_validation_matkul_id_required(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $payload = $this->defaultPayload;
        unset($payload['matkul_id']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('matkul_id');
    }

    public function test_update_validation_matkul_id_exists(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $payload = $this->defaultPayload;
        $payload['matkul_id'] = 999;

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('matkul_id');
    }

    public function test_update_validation_matkul_id_unique(): void
    {
        $model = $this->test_can_create_matakuliah_asesor();
        $model2 = MataKuliahAsesor::where('id', '!=', $model->id)->first();
        $payload = $this->defaultPayload;
        $payload['asesor_id'] = $model->asesor_id;

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model2->id), $payload)
            ->assertJsonValidationErrors('matkul_id');
    }
}
