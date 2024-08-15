<?php

namespace Tests\Feature\Akademik;

use App\Models\Master\KabupatenKota;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class KabupatenTest extends CRUDTestCase
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
            'provinsi_id' => '1',
            'kode' => '111.1',
            'nama' => 'Malang Kidul',
        ];
        $this->route = 'master.kabupaten-kota.';
        $this->table = 'ref_kabupaten_kota';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('master.kabupaten-kota');
        $this->setBaseModel(KabupatenKota::class);
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

    public function test_can_create_kabupaten(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_kabupaten()
    {
        $model = $this->test_can_create_kabupaten();
        $updatePayload = array_merge($this->defaultPayload, [
            'provinsi_id' => '2',
            'kode' => '111.2',
            'nama' => 'Malang Barat',
        ]);
        $response = $this->testUpdate(model: $model, attributes: $updatePayload);
    }

    public function test_can_delete_kabupaten()
    {
        $model = $this->test_can_create_kabupaten();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_kabupaten()
    {
        $model = $this->test_can_delete_kabupaten();
        $this->model = $model;
        $response = $this->testRestore(model: $model);
    }

    public function test_validation_create_kabupaten_with_kode_required()
    {
        $this->defaultPayload['kode'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_kabupaten_with_kode_regex()
    {
        $this->defaultPayload['kode'] = 'A&d123.';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_kabupaten_with_kode_unique()
    {
        $this->defaultPayload['kode'] = '11.1';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_kabupaten_with_nama_required()
    {
        $this->defaultPayload['nama'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_kabupaten_with_nama_string()
    {
        $this->defaultPayload['nama'] = 123;
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_update_kabupaten_with_kode_required()
    {
        $model = $this->test_can_create_kabupaten();
        $this->defaultPayload['kode'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kabupaten_with_kode_regex()
    {
        $model = $this->test_can_create_kabupaten();
        $this->defaultPayload['kode'] = 'A&d123';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kabupaten_with_kode_unique()
    {
        $model = $this->test_can_create_kabupaten();
        $this->defaultPayload['kode'] = '11.1';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kabupaten_with_kode_unique_except_self()
    {
        $model = $this->test_can_create_kabupaten();
        $this->testUpdate($model, $this->defaultPayload, status: 200);
    }

    public function test_validation_update_kabupaten_with_nama_required()
    {
        $model = $this->test_can_create_kabupaten();
        $this->defaultPayload['nama'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kabupaten_with_nama_string()
    {
        $model = $this->test_can_create_kabupaten();
        $this->defaultPayload['nama'] = 123;
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kabupaten_with_nama_alpha_spaces()
    {
        $model = $this->test_can_create_kabupaten();
        $this->defaultPayload['nama'] = 'Panjen Baru 123';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }
}
