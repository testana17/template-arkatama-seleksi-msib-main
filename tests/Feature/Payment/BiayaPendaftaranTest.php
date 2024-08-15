<?php

namespace Tests\Feature\Payment;

use App\Models\Akademik\ProgramStudi;
use App\Models\Payment\PaymentProdi;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class BiayaPendaftaranTest extends CRUDTestCase
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
            'is_free_ukt' => '0',
            'biaya_ukt' => 1000000,
            'biaya_pendaftaran' => 250000,
        ];

        $this->route = 'payment.biaya-pendaftaran.';
        $this->table = 'matakuliah';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('payment.biaya-pendaftaran');
        $this->setBaseModel(PaymentProdi::class);
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

    public function test_can_create_biaya_pendaftaran(): Model
    {
        PaymentProdi::truncate();

        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_biaya_pendaftaran()
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'is_free_ukt' => '1',
                'biaya_ukt' => 400000,
                'biaya_pendaftaran' => 2000000,
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_can_delete_biaya_pendaftaran()
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_biaya_pendaftaran()
    {
        $model = $this->test_can_delete_biaya_pendaftaran();
        $response = $this->testRestore(model: $model);
    }

    public function test_create_validation_biaya_pendaftaran_prodi_id_required(): void
    {
        $this->defaultPayload['prodi_id'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_biaya_pendaftaran_prodi_id_exists(): void
    {
        $this->defaultPayload['prodi_id'] = 'invalid-id';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_biaya_pendaftaran_is_free_ukt_required(): void
    {
        $this->defaultPayload['is_free_ukt'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_biaya_pendaftaran_is_free_ukt_in(): void
    {
        $this->defaultPayload['is_free_ukt'] = 'invalid-value';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_biaya_pendaftaran_biaya_ukt_required(): void
    {
        $this->defaultPayload['biaya_ukt'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_biaya_pendaftaran_biaya_pendaftaran_required(): void
    {
        $this->defaultPayload['biaya_pendaftaran'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_update_validation_biaya_pendaftaran_prodi_id_required(): void
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $this->defaultPayload['prodi_id'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_biaya_pendaftaran_prodi_id_exists(): void
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $this->defaultPayload['prodi_id'] = 'invalid-id';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_biaya_pendaftaran_is_free_ukt_required(): void
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $this->defaultPayload['is_free_ukt'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_biaya_pendaftaran_is_free_ukt_in(): void
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $this->defaultPayload['is_free_ukt'] = 'invalid-value';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_biaya_pendaftaran_biaya_ukt_required(): void
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $this->defaultPayload['biaya_ukt'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_biaya_pendaftaran_biaya_pendaftaran_required(): void
    {
        $model = $this->test_can_create_biaya_pendaftaran();
        $this->defaultPayload['biaya_pendaftaran'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }
}
