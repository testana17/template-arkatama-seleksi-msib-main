<?php

namespace Tests\Feature\Setting\User;

use App\Models\Setting\Menus;
use App\Models\User;
use App\Models\User\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\CRUDTestCase;

class RoleTest extends CRUDTestCase
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
            'name' => 'Test_Role',
            'guard_name' => 'web',
        ];

        $this->route = 'users.role.';
        $this->table = 'roles';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('users.role');
        $this->setBaseModel(Role::class);
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

    public function test_can_create_role(): Model
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

    public function test_create_validation_name_unique(): void
    {
        $model = $this->test_can_create_role();
        $this->testCreate(attributes: $this->defaultPayload, status: 422);
    }

    public function test_create_validation_guard_name_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['guard_name']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_can_update_role()
    {
        $model = $this->test_can_create_role();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'name' => 'Test_role_Update',
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_update_validation_name_required(): void
    {
        $model = $this->test_can_create_role();
        $payload = $this->defaultPayload;
        unset($payload['name']);
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_update_validation_name_unique(): void
    {
        $model = Role::first();
        $payload = [
            'name' => $model->name,
        ];
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_update_validation_guard_name_required(): void
    {
        $model = $this->test_can_create_role();
        $payload = $this->defaultPayload;
        unset($payload['guard_name']);
        $this->testUpdate($model, $payload, status: 422);
    }

    public function test_can_delete_role()
    {
        $model = $this->test_can_create_role();
        $response = $this->actingAs($this->adminUser)->deleteJson(route($this->route.'destroy', $model->id));
    }

    public function test_export_json(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('users.permission.export'));
        $response->assertStatus(200);
    }

    public function test_redirect_detail(): void
    {
        $model = $this->test_can_create_role();
        $response = $this->actingAs($this->adminUser)->get(route($this->route.'show', $model->id));
        $response->assertStatus(200);
    }

    public function test_update_permissions(): void
    {
        $arr = [];
        $model = Menus::with('accesses')->get();
        foreach ($model as $m) {
            if ($m->id == 1) {
                foreach ($m->accesses as $a) {
                    $arr[str_replace('.', '_', $a->module)] = 'on';
                }
                $arr[str_replace('.', '_', $m->module)] = 'on';
            }
        }

        $response = $this->actingAs($this->adminUser)->put(route($this->route.'permissions', 1), $arr);
        $response->assertStatus(200);
    }

    public function test_menu_has_access()
    {
        $this->test_update_permissions();
        $model = Menus::with('accesses')->where('id', 1)->first();

        $this->actingAs($this->adminUser);

        $response = $this->assertEquals(Auth::user()->hasAnyPermission([$model->module, ...$model->accesses->pluck('module')->toArray()]), true);

    }

    public function test_user_permission_has_been_syncronized()
    {
        $permission = [];
        $this->test_update_permissions();
        $model_a = Menus::with('accesses')->where('id', 1)->first();
        $model_b = Menus::with('accesses')->whereNot('id', 1)->get();

        foreach ($model_b as $b) {
            foreach ($b->accesses as $a) {
                $permission[] = $a->module;
            }
            $permission[] = $b->module;
        }

        $this->actingAs($this->adminUser);

        $response_a = $this->assertEquals(Auth::user()->hasAnyPermission([$model_a->module, ...$model_a->accesses->pluck('module')->toArray()]), true);
        $response_b = $this->assertEquals(Auth::user()->hasAnyPermission($permission), false);

    }
}
