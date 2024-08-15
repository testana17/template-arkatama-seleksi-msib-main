<?php

namespace Tests\Feature\Setting;

use App\Models\Setting\BackupSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class BackupDatabaseTest extends CRUDTestCase
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
            'name' => 'Backup Formulirs',
            'frequency' => 'monthly',
            'time' => '00:00',
            'tables' => ['formulirs'],
        ];

        $this->route = 'setting.backup.';
        $this->table = 'backupschedule-table';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('setting.backup');
        $this->setBaseModel(BackupSchedule::class);
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

    public function test_can_create_backup_schedule(): Model
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

    public function test_create_validation_name_unique(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $payload = $this->defaultPayload;
        $payload['name'] = $model->name;
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_frequency_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['frequency']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_frequency_in(): void
    {
        $payload = $this->defaultPayload;
        $payload['frequency'] = 'invalid-frequency';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_time_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['time']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_tables_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['tables']);
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_create_validation_tables_array(): void
    {
        $payload = $this->defaultPayload;
        $payload['tables'] = 'invalid-tables';
        $this->testCreate(attributes: $payload, status: 422);
    }

    public function test_can_update_backup_schedule(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'name' => 'Backup Formulirs Updated',
                'frequency' => 'daily',
                'time' => '01:00',
                'tables' => ['formulirs', 'users'],
            ]
        );

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $updatePayload)
            ->assertStatus(200);
        $this->assertDatabaseHas('backup_schedules', collect($updatePayload)->except('tables')->toArray());
        foreach ($updatePayload['tables'] as $table) {
            $this->assertDatabaseHas('backup_schedule_tables', [
                'backup_schedule_id' => $model->id,
                'table_name' => $table,
            ]);
        }
    }

    public function test_update_validation_name_required(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        unset($updatePayload['name']);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_string(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        $updatePayload['name'] = 123;
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_max(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        $updatePayload['name'] = str_repeat('a', 256);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_name_unique(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $model2 = BackupSchedule::factory()->create();
        $updatePayload = $this->defaultPayload;
        $updatePayload['name'] = $model2->name;
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_frequency_required(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        unset($updatePayload['frequency']);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_frequency_in(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        $updatePayload['frequency'] = 'invalid-frequency';
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_time_required(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        unset($updatePayload['time']);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_tables_required(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        unset($updatePayload['tables']);
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_update_validation_tables_array(): void
    {
        $model = $this->test_can_create_backup_schedule();
        $updatePayload = $this->defaultPayload;
        $updatePayload['tables'] = 'invalid-tables';
        $this->testUpdate($model, $updatePayload, status: 422);
    }

    public function test_can_delete_backup_schedule()
    {
        $model = $this->test_can_create_backup_schedule();
        $response = $this->testDelete(model: $model, isSoftDeleting: false);

        return $model;
    }
}
