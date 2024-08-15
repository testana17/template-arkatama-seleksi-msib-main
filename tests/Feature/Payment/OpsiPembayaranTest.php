<?php

namespace Tests\Feature\Payment;

use App\Models\Payment\PaymentGateway;
use App\Models\Payment\PaymentGatewayOption;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class OpsiPembayaranTest extends CRUDTestCase
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
            'payment_gateway_id' => PaymentGateway::first()->id,
            'is_active' => '1',
        ];

        $this->route = 'payment.payment-option.';
        $this->table = 'payment_gateway_options';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('payment.payment-option');
        $this->setBaseModel(PaymentGatewayOption::class);
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

    public function test_can_create_opsi_pembayaran(): Model
    {
        PaymentGatewayOption::truncate();
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_opsi_pembayaran()
    {
        $model = $this->test_can_create_opsi_pembayaran();
        $paymentGateway = PaymentGateway::create([
            'name' => 'Payment Gateway Test',
            'helper' => 'TestGateway.php',
        ]);

        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'payment_gateway_id' => $paymentGateway->id,
                'is_active' => '1',
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_can_delete_opsi_pembayaran()
    {
        $model = $this->test_can_create_opsi_pembayaran();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_opsi_pembayaran()
    {
        $model = $this->test_can_delete_opsi_pembayaran();
        $response = $this->testRestore(model: $model);
    }

    public function test_create_validation_opsi_pembayaran_payment_gateway_id_required(): void
    {
        $this->defaultPayload['payment_gateway_id'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_opsi_pembayaran_payment_gateway_id_exists(): void
    {
        $this->defaultPayload['payment_gateway_id'] = 'invalid-id';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_opsi_pembayaran_is_active_required(): void
    {
        $this->defaultPayload['is_active'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_opsi_pembayaran_is_active_in(): void
    {
        $this->defaultPayload['is_active'] = 'invalid-value';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_update_validation_opsi_pembayaran_payment_gateway_id_required(): void
    {
        $model = $this->test_can_create_opsi_pembayaran();
        $this->defaultPayload['payment_gateway_id'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_opsi_pembayaran_payment_gateway_id_exists(): void
    {
        $model = $this->test_can_create_opsi_pembayaran();
        $this->defaultPayload['payment_gateway_id'] = 'invalid-id';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_opsi_pembayaran_is_active_required(): void
    {
        $model = $this->test_can_create_opsi_pembayaran();
        $this->defaultPayload['is_active'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_opsi_pembayaran_is_active_in(): void
    {
        $model = $this->test_can_create_opsi_pembayaran();
        $this->defaultPayload['is_active'] = 'invalid-value';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }
}
