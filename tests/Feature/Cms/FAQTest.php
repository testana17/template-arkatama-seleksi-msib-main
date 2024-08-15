<?php

namespace Tests\Feature\Cms;

use App\Models\Cms\FAQs;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CRUDTestCase;

class FAQTest extends CRUDTestCase
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
            'question' => 'FAQ Test',
            'answer' => 'FAQ Answer',
            'is_active' => '1',
        ];

        $this->route = 'cms.faqs.';
        $this->table = 'faqs';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('cms.faqs');
        $this->setBaseModel(FAQs::class);
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

    public function test_can_create_faq(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_faq()
    {
        $model = $this->test_can_create_faq();

        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'question' => 'FAQ Test Updated',
                'answer' => 'FAQ Answer Updated',
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_can_delete_faq()
    {
        $model = $this->test_can_create_faq();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_faq()
    {
        $model = $this->test_can_delete_faq();
        $response = $this->testRestore(model: $model);
    }

    public function test_can_create_faq_question_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['question']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_can_create_faq_question_min_3_characters()
    {
        $payload = $this->defaultPayload;
        $payload['question'] = 'ab';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_can_create_faq_question_max_100_characters()
    {
        $payload = $this->defaultPayload;
        $payload['question'] = str_repeat('a', 101);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_can_create_faq_question_string()
    {
        $payload = $this->defaultPayload;
        $payload['question'] = 101;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_can_create_faq_question_cannot_contains_only_numbers()
    {
        $payload = $this->defaultPayload;
        $payload['question'] = '123';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_can_create_faq_question_cannot_contains_only_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['question'] = '!!!';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_can_create_faq_answer_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['answer']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_can_create_faq_answer_string()
    {
        $payload = $this->defaultPayload;
        $payload['answer'] = 123;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_can_create_faq_answer_max_255_characters()
    {
        $payload = $this->defaultPayload;
        $payload['answer'] = str_repeat('a', 256);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_can_create_faq_answer_cannot_contains_only_numbers()
    {
        $payload = $this->defaultPayload;
        $payload['answer'] = '123';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_can_create_faq_answer_cannot_contains_only_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['answer'] = '!!!';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_can_create_faq_is_active_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['is_active']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }

    public function test_can_create_faq_is_active_in()
    {
        $payload = $this->defaultPayload;
        $payload['is_active'] = 2;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }

    public function test_update_validation_question_is_required()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['question'] = null;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_update_validation_question_min_3_characters()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['question'] = 'ab';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_update_validation_question_max_100_characters()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['question'] = str_repeat('a', 101);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_update_validation_question_cannot_contains_only_numbers()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['question'] = '123';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_update_validation_question_cannot_contains_only_symbols()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['question'] = '!!!';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('question');
    }

    public function test_update_validation_answer_is_required()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        unset($payload['answer']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_update_validation_answer_string()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['answer'] = 123;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_update_validation_answer_max_255_characters()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['answer'] = str_repeat('a', 256);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_update_validation_answer_cannot_contains_only_numbers()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['answer'] = '123';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_update_validation_answer_cannot_contains_only_symbols()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['answer'] = '!!!';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('answer');
    }

    public function test_update_validation_is_active_required()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        unset($payload['is_active']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }

    public function test_update_validation_is_active_in()
    {
        $model = $this->test_can_create_faq();
        $payload = $this->defaultPayload;
        $payload['is_active'] = 2;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }
}
