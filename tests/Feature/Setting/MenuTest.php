<?php

namespace Tests\Feature\Setting;

use App\Models\Setting\Menus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class MenuTest extends CRUDTestCase
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
            'name' => 'Test_Menu',
            'module' => 'Test Module',
            'slug' => 'test-slug',
            'icon' => 'test-icon',
            'url' => 'test-url',
            'parent_id' => null,
            'order' => 1,
            'type' => 'menu',
            'target' => '_self',
            'location' => 'sidebar',
            'is_active' => '1',
        ];

        $this->route = 'setting.menus.';
        $this->table = 'menus';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('setting.menus');
        $this->setBaseModel(Menus::class);
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

    public function test_can_create_menu(): Model
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

    public function test_create_validation_name_max(): void
    {
        $payload = $this->defaultPayload;
        $payload['name'] = str_repeat('a', 201);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_module_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['module']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_module_max(): void
    {
        $payload = $this->defaultPayload;
        $payload['module'] = str_repeat('a', 201);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_module_unique(): void
    {
        $model = $this->test_can_create_menu();
        $this->testCreate(attributes: $this->defaultPayload, status: 422);
    }

    public function test_create_validation_slug_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['slug']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_slug_max(): void
    {
        $payload = $this->defaultPayload;
        $payload['slug'] = str_repeat('a', 201);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_slug_unique(): void
    {
        $model = $this->test_can_create_menu();
        $this->testCreate(attributes: $this->defaultPayload, status: 422);
    }

    public function test_create_validation_url_max(): void
    {
        $payload = $this->defaultPayload;
        $payload['url'] = str_repeat('a', 201);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_url_unique(): void
    {
        $model = $this->test_can_create_menu();
        $this->testCreate(attributes: $this->defaultPayload, status: 422);
    }

    public function test_create_validation_icon_max(): void
    {
        $payload = $this->defaultPayload;
        $payload['icon'] = str_repeat('a', 201);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_parent_id_exists(): void
    {
        $payload = $this->defaultPayload;
        $payload['parent_id'] = 999;
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_order_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['order']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_order_integer(): void
    {
        $payload = $this->defaultPayload;
        $payload['order'] = 'test';
        $this->testCreate(attributes: $payload, status: 422);
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
        $payload['type'] = 'test';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_target_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['target']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_target_in(): void
    {
        $payload = $this->defaultPayload;
        $payload['target'] = 'test';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_location_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['location']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_location_in(): void
    {
        $payload = $this->defaultPayload;
        $payload['location'] = 'test';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_is_active_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['is_active']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_is_active_in(): void
    {
        $payload = $this->defaultPayload;
        $payload['is_active'] = 'test';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_can_update_menu()
    {
        $model = $this->test_can_create_menu();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'name' => 'Test_Menu_Update',
            ],
        );

        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $updatePayload);
        $response->assertStatus(200);
    }

    public function test_update_validation_name_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['name']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_name_max(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['name'] = str_repeat('a', 201);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_module_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['module']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_module_max(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['module'] = str_repeat('a', 201);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_module_unique(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['module'] = 'Test Module';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_slug_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['slug']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_slug_max(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['slug'] = str_repeat('a', 201);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_slug_unique(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['slug'] = 'test-slug';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_url_max(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['url'] = str_repeat('a', 201);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_url_unique(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['url'] = 'test-url';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_icon_max(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['icon'] = str_repeat('a', 201);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_parent_id_exists(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['parent_id'] = 999;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_order_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['order']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_order_integer(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['order'] = 'test';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_type_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['type']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_type_in(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['type'] = 'test';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_target_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['target']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_target_in(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['target'] = 'test';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_location_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['location']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_location_in(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['location'] = 'test';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_is_active_required(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        unset($payload['is_active']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_update_validation_is_active_in(): void
    {
        $model = $this->test_can_create_menu();
        $payload = $this->defaultPayload;
        $payload['is_active'] = 'test';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
    }

    public function test_can_delete_menu()
    {
        $model = $this->test_can_create_menu();
        $response = $this->actingAs($this->adminUser)->deleteJson(route($this->route.'destroy', $model->id));

        return $model;
    }

    public function test_can_export_json(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route($this->route.'exportjson'));
        $response->assertStatus(200);
    }
}
