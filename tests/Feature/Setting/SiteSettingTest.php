<?php

namespace Tests\Feature\Setting;

use App\Models\Setting\SiteSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class SiteSettingTest extends CRUDTestCase
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
            'type' => 'site-identity',
            'name' => 'Test_Site_Setting',
            'value' => 'Test Value',
            'description' => 'Test Description',
        ];

        $this->route = 'setting.site-settings.';
        $this->table = 'site-settings';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('setting.site-settings');
        $this->setBaseModel(SiteSetting::class);
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

    public function test_can_create_site_setting(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_create_validation_type_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['type']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_type_in(): void
    {
        $payload = $this->defaultPayload;
        $payload['type'] = 'invalid-type';
        $this->testCreate(attributes: $payload, status: 422);
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

    public function test_create_validation_name_min_3(): void
    {
        $payload = $this->defaultPayload;
        $payload['name'] = 'aa';
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
        $this->test_can_create_site_setting();
        $this->testCreate(attributes: $this->defaultPayload, status: 422);
    }

    public function test_create_validation_value_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['value']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_value_min_1(): void
    {
        $payload = $this->defaultPayload;
        $payload['value'] = '';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_value_max_255(): void
    {
        $payload = $this->defaultPayload;
        $payload['value'] = str_repeat('a', 256);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_can_update_site_setting()
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'type' => 'profile',
                'name' => 'Test_Site_Setting_Updated',
                'value' => 'Test Value Updated',
                'description' => 'Test Description Updated',
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_update_validation_type_required(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        unset($updatePayload['type']);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_type_in(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        $updatePayload['type'] = 'invalid-type';
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_required(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        unset($updatePayload['name']);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_alpha_dash_only(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        $updatePayload['name'] = 'Test System Setting';
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_min_3(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        $updatePayload['name'] = 'aa';
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_max_200(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        $updatePayload['name'] = str_repeat('a', 201);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_unique(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = [
            'name' => $model->name,
        ];
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_value_required(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        unset($updatePayload['value']);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_value_min_1(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        $updatePayload['value'] = '';
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_value_max_255(): void
    {
        $model = $this->test_can_create_site_setting();
        $updatePayload = $this->defaultPayload;
        $updatePayload['value'] = str_repeat('a', 256);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_can_delete_site_setting()
    {
        $model = $this->test_can_create_site_setting();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_site_setting()
    {
        $model = $this->test_can_delete_site_setting();
        $response = $this->actingAs($this->adminUser)->post(route($this->route.'restore', ['id' => $model->id]));
    }
}
