<?php

namespace Tests\Feature\Setting;

use App\Models\Setting\SystemSettingModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class SystemSettingTest extends CRUDTestCase
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
            'name' => 'Test_System_Setting',
            'value' => 'Test Value',
            'description' => 'Test Description',
            'is_active' => '1',
        ];

        $this->route = 'setting.system-setting.';
        $this->table = 'system_settings';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('setting.system-setting');
        $this->setBaseModel(SystemSettingModel::class);
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

    public function test_can_create_system_setting(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_create_validation_name_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['name']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_name_alpha_dash_only(): void
    {
        $payload = $this->defaultPayload;
        $payload['name'] = 'Test System Setting';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_name_max_200(): void
    {
        $payload = $this->defaultPayload;
        $payload['name'] = str_repeat('a', 201);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_name_unique(): void
    {
        $this->test_can_create_system_setting();
        $this->testCreate(attributes: $this->defaultPayload, status: 422);
    }

    public function test_create_validation_value_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['value']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_value_max_255(): void
    {
        $payload = $this->defaultPayload;
        $payload['value'] = str_repeat('a', 256);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_can_update_system_setting()
    {
        $model = $this->test_can_create_system_setting();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'name' => 'Updated_Test_System_Setting',
                'value' => 'Updated Test Value',
                'description' => 'Updated Test Description',
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_update_validation_name_required(): void
    {
        $model = $this->test_can_create_system_setting();
        $payload = $this->defaultPayload;
        unset($payload['name']);
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_update_validation_name_alpha_dash_only(): void
    {
        $model = $this->test_can_create_system_setting();
        $payload = $this->defaultPayload;
        $payload['name'] = 'Test System Setting';
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_update_validation_name_max_200(): void
    {
        $model = $this->test_can_create_system_setting();
        $payload = $this->defaultPayload;
        $payload['name'] = str_repeat('a', 201);
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_update_validation_name_unique(): void
    {
        $model = SystemSettingModel::first();
        $payload = [
            'name' => $model->name,
        ];
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_update_validation_value_required(): void
    {
        $model = $this->test_can_create_system_setting();
        $payload = $this->defaultPayload;
        unset($payload['value']);
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_update_validation_value_max_255(): void
    {
        $model = $this->test_can_create_system_setting();
        $payload = $this->defaultPayload;
        $payload['value'] = str_repeat('a', 256);
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_can_delete_system_setting()
    {
        $model = $this->test_can_create_system_setting();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_system_setting()
    {
        $model = $this->test_can_delete_system_setting();
        $response = $this->actingAs($this->adminUser)->post(route($this->route.'restore', ['id' => $model->id]));
    }
}
