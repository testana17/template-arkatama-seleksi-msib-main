<?php

namespace Tests\Feature\Payment;

use App\Models\Payment\PaymentGateway;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\CRUDTestCase;

class PaymentGatewaySettingTest extends CRUDTestCase
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
            'name' => 'Tripay',
            'helper' => 'TripayGateway.php',
        ];

        $this->route = 'payment.payment-gateway.';
        $this->table = 'payment_gateways';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('payment.payment-gateway');
        $this->setBaseModel(PaymentGateway::class);
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

    // Payment Gateway Setting

    public function test_can_create_payment_gateway(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_payment_gateway()
    {
        $model = $this->test_can_create_payment_gateway();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'name' => 'Tripay test',
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_can_delete_payment_gateway()
    {
        $model = $this->test_can_create_payment_gateway();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_payment_gateway()
    {
        $model = $this->test_can_delete_payment_gateway();
        $response = $this->testRestore(model: $model);
    }

    public function test_create_validation_payment_gateway_name_required(): void
    {
        $this->defaultPayload['name'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_name_string(): void
    {
        $this->defaultPayload['name'] = 123;
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_name_min_3(): void
    {
        $this->defaultPayload['name'] = 'ab';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_name_cannot_only_number(): void
    {
        $this->defaultPayload['name'] = '123';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_name_cannot_only_symbol(): void
    {
        $this->defaultPayload['name'] = '!!!';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_name_unique(): void
    {
        $this->test_can_create_payment_gateway();
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_helper_required(): void
    {
        $this->defaultPayload['helper'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_helper_exists_rule(): void
    {
        $this->defaultPayload['helper'] = 'invalid-helper.php';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_create_validation_payment_gateway_helper_unique(): void
    {
        $this->test_can_create_payment_gateway();
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_name_required(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['name'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_name_string(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['name'] = 123;
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_name_min_3(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['name'] = 'ab';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_name_cannot_only_number(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['name'] = '123';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_name_cannot_only_symbol(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['name'] = '!!!';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_name_unique(): void
    {
        $model = $this->test_can_create_payment_gateway();

        $this->defaultPayload['name'] = 'Midtrans';

        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_helper_required(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['helper'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_helper_exists_rule(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['helper'] = 'invalid-helper.php';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_update_validation_payment_gateway_helper_unique(): void
    {
        $model = $this->test_can_create_payment_gateway();
        $this->defaultPayload['helper'] = 'MidtransGateway.php';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    //Payment Gateway Channel

    public function test_can_add_payment_channel_on_payment_gateway_setting()
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(201);

        return $response->json('data');
    }

    public function test_can_update_payment_channel_on_payment_gateway_setting()
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'otc',
            'name' => 'Alfamidi',
            'kode' => '001',
            'fee_customer_flat' => 5000,
            'fee_customer_percent' => null,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(200);

        return $response->json('data');
    }

    public function test_can_delete_payment_channel_on_payment_gateway_setting()
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $response = $this->actingAs($this->adminUser)->deleteJson(route('payment.payment-gateway.channel.destroy', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]));
        $response->assertStatus(200);
    }

    public function test_can_restore_payment_channel_on_payment_gateway_setting()
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $response = $this->actingAs($this->adminUser)->deleteJson(route('payment.payment-gateway.channel.destroy', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]));
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.restore', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]));
        $response->assertStatus(200);
    }

    public function test_add_channel_validation_payment_type_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => '',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_payment_type_in(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'invalid',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_name_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => '',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_name_string(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 123,
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_name_min_3(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'ab',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_name_cannot_only_number(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => '123',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_name_cannot_only_symbol(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => '!!!',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_kode_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => '',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_kode_alpha_dash(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay!',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_fee_customer_flat_numeric(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => 'invalid',
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_fee_customer_flat_required_if_fee_customer_percent_null(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => null,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_fee_customer_percent_numeric(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 'invalid',
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_fee_customer_percent_required_if_fee_customer_flat_null(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => null,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_minimum_fee_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => '',
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_minimum_fee_numeric(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 'invalid',
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_maximum_fee_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => '',
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_maximum_fee_numeric(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 'invalid',
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_is_active_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_add_channel_validation_is_active_in(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => 'invalid',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.channel.store', $paymentGateway->id), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_payment_type_required(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => '',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_payment_type_in(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'invalid',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_name_required(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => '',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_name_string(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 123,
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_name_min_3(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'ab',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_name_cannot_only_number(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => '123',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_name_cannot_only_symbol(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => '!!!',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_kode_required(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => '',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_kode_alpha_dash(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay!',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_fee_customer_flat_numeric(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => 'invalid',
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_fee_customer_flat_required_if_fee_customer_percent_null(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => null,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_fee_customer_percent_numeric(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 'invalid',
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_fee_customer_percent_required_if_fee_customer_flat_null(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => null,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_minimum_fee_required(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => '',
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_minimum_fee_numeric(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 'invalid',
            'maximum_fee' => 0,
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_maximum_fee_required(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => '',
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_maximum_fee_numeric(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 'invalid',
            'is_active' => '1',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_is_active_required(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => '',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_channel_validation_is_active_in(): void
    {
        $channel = $this->test_can_add_payment_channel_on_payment_gateway_setting();
        $payload = [
            'payment_type' => 'e_wallet',
            'name' => 'GoPay',
            'kode' => 'gopay',
            'fee_customer_flat' => null,
            'fee_customer_percent' => 2,
            'minimum_fee' => 0,
            'maximum_fee' => 0,
            'is_active' => 'invalid',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.channel.update', ['payment_gateway' => $channel['payment_gateway_id'], 'channel' => $channel['id']]), $payload);
        $response->assertStatus(422);
    }

    // Konfigurasi Payment Gateway

    public function test_can_add_payment_gateway_configuration()
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => 'client_key',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(201);

        return $response->json('data');
    }

    public function test_can_update_payment_gateway_configuration()
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 'updated_client_key',
            'value' => 'updated_client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(200);

        return $response->json('data');
    }

    public function test_can_delete_payment_gateway_configuration()
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $response = $this->actingAs($this->adminUser)->deleteJson(route('payment.payment-gateway.config.destroy', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]));
        $response->assertStatus(200);
    }

    public function test_add_config_validation_key_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => '',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_key_string(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => 123,
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_key_min_3(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => 'ab',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_key_max_100(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => Str::random(101),
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_key_unique(): void
    {
        $paymentGateway = PaymentGateway::where('name', 'Midtrans')->first();
        $payload = [
            'key' => 'PRODUCTION_SNAP_URL',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_key_alpha_dash_only(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => 'client key',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_value_required(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => 'client_key',
            'value' => '',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_value_string(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => 'client_key',
            'value' => 123,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_add_config_validation_value_max_255(): void
    {
        $paymentGateway = $this->test_can_create_payment_gateway();
        $payload = [
            'key' => 'client_key',
            'value' => Str::random(256),
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_key_required(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => '',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_key_string(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 123,
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_key_min_3(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 'ab',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_key_max_100(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => Str::random(101),
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_key_unique(): void
    {
        $paymentGateway = PaymentGateway::where('name', 'Midtrans')->first();
        $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 'PRODUCTION_SNAP_URL',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('payment.payment-gateway.config.store', ['payment_gateway' => $paymentGateway['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_key_alpha_dash_only(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 'client key',
            'value' => 'client_key_value',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_value_required(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 'client_key',
            'value' => '',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_value_string(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 'client_key',
            'value' => 123,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_config_validation_value_max_255(): void
    {
        $config = $this->test_can_add_payment_gateway_configuration();
        $payload = [
            'key' => 'client_key',
            'value' => Str::random(256),
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('payment.payment-gateway.config.update', ['payment_gateway' => $config['payment_gateway_id'], 'konfigurasi' => $config['id']]), $payload);
        $response->assertStatus(422);
    }
}
