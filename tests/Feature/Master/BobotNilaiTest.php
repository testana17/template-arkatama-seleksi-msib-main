<?php

namespace Tests\Feature\Akademik;

use App\Models\Master\BobotNilai;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class BobotNilaiTest extends CRUDTestCase
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
            'nilai_min' => 0,
            'nilai_max' => 120,
            'nilai_huruf' => 'S',
            'is_active' => '1',
        ];
        $this->route = 'master.bobot-nilai.';
        $this->table = 'ref_bobot_nilai';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('master.bobot-nilai');
        $this->setBaseModel(BobotNilai::class);
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

    public function test_can_create_bobot_nilai(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_bobot_nilai()
    {
        $model = $this->test_can_create_bobot_nilai();
        $updatePayload = array_merge($this->defaultPayload, [
            'nilai_min' => 100,
            'nilai_max' => 150,
            'nilai_huruf' => 'SS',
            'is_active' => '1',
        ]);
        $response = $this->testUpdate(model: $model, attributes: $updatePayload);
    }

    public function test_can_delete_bobot_nilai()
    {
        $model = $this->test_can_create_bobot_nilai();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_bobot_nilai()
    {
        $model = $this->test_can_delete_bobot_nilai();
        $response = $this->testRestore(model: $model);
    }

    public function test_validation_create_bobot_nilai_with_nilai_min_required()
    {
        $this->defaultPayload['nilai_min'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_bobot_nilai_with_nilai_max_required()
    {
        $this->defaultPayload['nilai_max'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_bobot_nilai_with_nilai_huruf_required()
    {
        $this->defaultPayload['nilai_huruf'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_bobot_nilai_with_nilai_huruf_max()
    {
        $this->defaultPayload['nilai_huruf'] = 'ABC';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_bobot_nilai_with_nilai_huruf_unique()
    {
        $this->testCreate(array_merge($this->defaultPayload, ['nilai_huruf' => 'A']), status: 422);
    }

    public function test_validation_update_bobot_nilai_with_nilai_min_required()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['nilai_min'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_create_bobot_nilai_with_nilai_max_greater_than_nilai_min()
    {
        $this->defaultPayload['nilai_max'] = 0;
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_update_bobot_nilai_with_nilai_max_greater_than_nilai_min()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['nilai_max'] = 0;
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_bobot_nilai_with_nilai_max_required()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['nilai_max'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_bobot_nilai_with_nilai_huruf_required()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['nilai_huruf'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_bobot_nilai_with_nilai_huruf_max()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['nilai_huruf'] = 'ABC';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_bobot_nilai_with_nilai_huruf_unique()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['nilai_huruf'] = 'A';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_bobot_nilai_with_nilai_huruf_unique_except_self()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->testUpdate($model, $this->defaultPayload, status: 200);
    }

    public function test_validation_update_bobot_nilai_with_is_active_required()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['is_active'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_bobot_nilai_with_is_active_boolean()
    {
        $model = $this->test_can_create_bobot_nilai();
        $this->defaultPayload['is_active'] = 'ABC';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }
}
