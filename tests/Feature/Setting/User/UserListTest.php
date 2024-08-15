<?php

namespace Tests\Feature\Setting\User;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role as ModelsRole;
use Tests\CRUDTestCase;

class UserListTest extends CRUDTestCase
{
    use DatabaseTransactions;

    private $adminUser;

    private $defaultPayload;

    private $route;

    private $table;

    private $state = [];

    public function setUp(): void
    {

        parent::setUp();
        $adminUser = User::where('email', 'admin@arkatama.test')->first();
        $this->adminUser = $adminUser;
        $this->defaultPayload = [
            'name' => 'Test_User_List',
            'email' => 'basofi.cucokmeong12@gmail.com',
            'password' => 'password',
            'role' => 'camaba',
        ];

        Notification::fake();
        $this->route = 'users.user-list.';
        $this->table = 'users';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('users.user-list');
        $this->setBaseModel(User::class);
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

    public function test_can_create_user_list(): Model
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

    public function test_create_validation_name_string(): void
    {
        $payload = $this->defaultPayload;
        $payload['name'] = 123;
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_name_max(): void
    {
        $payload = $this->defaultPayload;
        $payload['name'] = str_repeat('a', 256);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_email_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['email']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_email_email(): void
    {
        $payload = $this->defaultPayload;
        $payload['email'] = 'test';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_email_max(): void
    {
        $payload = $this->defaultPayload;
        $payload['email'] = str_repeat('a', 256).'@mail.com';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_email_unique(): void
    {
        $model = $this->test_can_create_user_list();
        $this->testCreate(attributes: $this->defaultPayload, status: 422);
    }

    public function test_create_validation_password_string(): void
    {
        $payload = $this->defaultPayload;
        $payload['password'] = 123;
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_password_min(): void
    {
        $payload = $this->defaultPayload;
        $payload['password'] = 'pass';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_role_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['role']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_role_exists(): void
    {
        $payload = $this->defaultPayload;
        $payload['role'] = 'test';
        $this->testCreate(attributes: $payload, status: 422);
    }

    // public function test_can_update_user_list()
    // {
    //     $model = $this->test_can_create_user_list();
    //     $updatePayload = array_merge(
    //         $this->defaultPayload,
    //         [
    //             'name' => 'Test_Update',
    //         ],
    //     );
    //     dd($updatePayload);
    //     $this->testUpdate($model, $updatePayload);
    // }

    public function test_can_delete_user_list()
    {
        $model = $this->test_can_create_user_list();
        $response = $this->actingAs($this->adminUser)->deleteJson(route($this->route.'destroy', $model->id));
    }

    public function test_impersonate(): mixed
    {
        $this->actingAs($this->adminUser);
        $model = $this->test_can_create_user_list();

        $this->get(route('users.impersonate', $model->id))
            ->assertStatus(302)
            ->assertRedirectToRoute('dashboard')
            ->assertSessionHas('impersonated_by', $this->adminUser->id);
        $this->assertEquals($model->id, Auth::id());

        return $model;
    }

    public function test_impersonate_has_permission(): void
    {
        $createdModel = $this->test_impersonate();
        $beforeImposonatePermissions = ModelsRole::findById(User::where('id', $this->adminUser->id)->first()->roles[0]->id)->permissions()->get(['name'])->pluck('name')->toArray();
        $afterImpersonatePermissions = ModelsRole::findById(User::where('id', $createdModel->id)->first()->roles[0]->id)->permissions()->get(['name'])->pluck('name')->toArray();

        $missingPermissions = array_diff($beforeImposonatePermissions, $afterImpersonatePermissions);

        $this->assertEquals(Auth::user()->hasAllPermissions($afterImpersonatePermissions), true);
        $this->assertEquals(Auth::user()->hasAnyPermission($missingPermissions), false);
    }

    public function test_request_reset_password_email(): void
    {

        $this->state['model'] = $this->test_can_create_user_list();

        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'reset-password', $this->state['model']->id), [
            'email' => $this->state['model']->email,
        ])->assertSuccessful();

        $this->assertNotNull($this->state['model']->email);

        Notification::assertSentTo($this->state['model'], ResetPassword::class, function ($notification) {
            $this->state['token_reset'] = $notification->token;

            return 1;
        });
    }

    public function test_can_access_reset_password_page()
    {
        $this->test_request_reset_password_email();
        $this->get(route('password.reset', $this->state['token_reset']))
            ->assertSuccessful()
            ->assertSee('Reset Password')
            ->assertSee('Email Address')
            ->assertSee('Password')
            ->assertSee('Confirm Password');
    }

    public function test_can_post_the_reset_password_form()
    {
        $this->test_request_reset_password_email();
        $this->post(route('password.update'), [
            'email' => $this->state['model']->email,
            'token' => $this->state['token_reset'],
            'password' => 'passwordchanged',
            'password_confirmation' => 'passwordchanged',
        ])->assertRedirectToRoute('dashboard');
    }
}
