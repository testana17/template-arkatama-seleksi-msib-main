<?php

namespace Tests\Feature\Cms;

use App\Models\Cms\SlideShow;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\CRUDTestCase;

class SlideShowsTest extends CRUDTestCase
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
            'name' => 'Slide Show Test',
            'description' => 'Slide Show Description',
            'is_active' => '0',
        ];

        $this->route = 'cms.slideshow.';
        $this->table = 'slideshow';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('cms.slideshow');
        $this->setBaseModel(SlideShow::class);
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

    public function test_can_create_slide_show(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_create_slide_item()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Test',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(201);

        return $response->json('data');
    }

    public function test_can_update_slide_item()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Update Slide Item Test',
            'caption' => 'Update Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 2,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(200);

        return $response->json('data');
    }

    public function test_can_delete_slide_item()
    {
        $slideItem = $this->test_can_create_slide_item();
        $response = $this->actingAs($this->adminUser)->deleteJson(route('cms.slideshow-item.destroy', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]));
        $response->assertStatus(200);

        return $slideItem;
    }

    public function test_can_restore_slide_item()
    {
        $slideItem = $this->test_can_delete_slide_item();
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.restore', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]));
        $response->assertStatus(200);

        return $slideItem;
    }

    public function test_can_update_slide_show()
    {
        $model = $this->test_can_create_slide_show();
        $updatePayload = array_merge(
            $this->defaultPayload,
            [
                'name' => 'Update Slide Show Test',
                'description' => 'Update Slide Show Description',
                'is_active' => '1',
            ],
        );
        $this->testUpdate($model, $updatePayload);
    }

    public function test_can_delete_slide_show()
    {
        $model = $this->test_can_create_slide_show();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_slide_show()
    {
        $model = $this->test_can_delete_slide_show();
        $response = $this->testRestore(model: $model);
    }

    public function test_can_create_slide_show_name_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['name']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_can_create_slide_show_name_min_3_characters()
    {
        $payload = $this->defaultPayload;
        $payload['name'] = 'ab';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_can_create_slide_show_name_max_100_characters()
    {
        $payload = $this->defaultPayload;
        $payload['name'] = str_repeat('a', 101);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_can_create_slide_show_name_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['name'] = 'Slide Show Test 123.pdf';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_can_create_slide_show_name_cannot_contains_only_numbers()
    {
        $payload = $this->defaultPayload;
        $payload['name'] = '123';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_can_create_slide_show_name_cannot_contains_only_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['name'] = '!!!';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_can_create_slide_show_description_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['description']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_can_create_slide_show_description_string()
    {
        $payload = $this->defaultPayload;
        $payload['description'] = 123;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_can_create_slide_show_description_max_255_characters()
    {
        $payload = $this->defaultPayload;
        $payload['description'] = str_repeat('a', 256);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_can_create_slide_show_description_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['description'] = 'Slide Show Description 123.pdf';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
    }

    public function test_can_create_slide_show_description_cannot_contains_only_numbers()
    {
        $payload = $this->defaultPayload;
        $payload['description'] = '123';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_can_create_slide_show_description_cannot_contains_only_symbols()
    {
        $payload = $this->defaultPayload;
        $payload['description'] = '!!!';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_can_create_slide_show_is_active_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['is_active']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }

    public function test_can_create_slide_show_is_active_in()
    {
        $payload = $this->defaultPayload;
        $payload['is_active'] = 2;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }

    public function test_update_validation_name_is_required()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['name'] = null;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_update_validation_name_min_3_characters()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['name'] = 'ab';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_update_validation_name_max_100_characters()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['name'] = str_repeat('a', 101);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_update_validation_name_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['name'] = 'Slide Show Test 123.pdf';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_name_cannot_contains_only_numbers()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['name'] = '123';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_update_validation_name_cannot_contains_only_symbols()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['name'] = '!!!';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_update_validation_description_is_required()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        unset($payload['description']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_update_validation_description_string()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['description'] = 123;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_update_validation_description_max_255_characters()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['description'] = str_repeat('a', 256);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_update_validation_description_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['description'] = 'Slide Show Description 123.pdf';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_description_cannot_contains_only_numbers()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['description'] = '123';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_update_validation_description_cannot_contains_only_symbols()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['description'] = '!!!';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('description');
    }

    public function test_update_validation_is_active_required()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        unset($payload['is_active']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }

    public function test_update_validation_is_active_in()
    {
        $model = $this->test_can_create_slide_show();
        $payload = $this->defaultPayload;
        $payload['is_active'] = 2;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('is_active');
    }

    public function test_create_validation_slide_item_title_required()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => null,
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_create_validation_slide_item_title_string()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 123,
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_create_validation_slide_item_title_min_3_characters()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'ab',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_create_validation_slide_item_title_max_100_characters()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => str_repeat('a', 101),
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_create_validation_slide_item_title_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title 123.pdf',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_slide_item_title_cannot_contains_only_numbers()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => '123',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_create_validation_slide_item_title_cannot_contains_only_symbols()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => '!!!',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_create_validation_slide_item_caption_required()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => null,
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_create_validation_slide_item_caption_string()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 123,
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_create_validation_slide_item_caption_max_255_characters()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => str_repeat('a', 256),
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_create_validation_slide_item_caption_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption 123.pdf',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
    }

    public function test_create_validation_slide_item_caption_cannot_contains_only_numbers()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => '123',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_create_validation_slide_item_caption_cannot_contains_only_symbols()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => '!!!',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_create_validation_slide_item_order_required()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => null,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('order');
    }

    public function test_create_validation_slide_item_order_integer()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 'abc',
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('order');
    }

    public function test_create_validation_slide_item_image_required()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => null,
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    public function test_create_validation_slide_item_image_image()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => 'image.jpg',
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    public function test_create_validation_slide_item_image_max_5000_kb()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 6000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    public function test_create_validation_slide_item_image_mimes()
    {
        $slideShow = $this->test_can_create_slide_show();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.pdf', 1000, 'application/pdf'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->postJson(route('cms.slideshow-item.store', ['slideshow' => $slideShow->id]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    public function test_update_validation_slide_item_title_required()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => null,
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_update_validation_slide_item_title_string()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 123,
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_update_validation_slide_item_title_min_3_characters()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'ab',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_update_validation_slide_item_title_max_100_characters()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => str_repeat('a', 101),
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_update_validation_slide_item_title_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title 123.pdf',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_slide_item_title_cannot_contains_only_numbers()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => '123',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_update_validation_slide_item_title_cannot_contains_only_symbols()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => '!!!',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    public function test_update_validation_slide_item_caption_required()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => null,
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_update_validation_slide_item_caption_string()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 123,
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_update_validation_slide_item_caption_max_255_characters()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => str_repeat('a', 256),
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_update_validation_slide_item_caption_can_contains_combination_of_alphabets_spaces_and_symbols()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption 123.pdf',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
    }

    public function test_update_validation_slide_item_caption_cannot_contains_only_numbers()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => '123',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_update_validation_slide_item_caption_cannot_contains_only_symbols()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => '!!!',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('caption');
    }

    public function test_update_validation_slide_item_order_required()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => null,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('order');
    }

    public function test_update_validation_slide_item_order_integer()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 1000, 'image/jpeg'),
            'order' => 'abc',
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('order');
    }

    public function test_update_validation_slide_item_image_required()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => null,
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    public function test_update_validation_slide_item_image_max_5000_kb()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.jpg', 6000, 'image/jpeg'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    public function test_update_validation_slide_item_image_mimes()
    {
        $slideItem = $this->test_can_create_slide_item();
        $payload = [
            'title' => 'Slide Item Title',
            'caption' => 'Slide Item Caption',
            'image' => UploadedFile::fake()->create('image.pdf', 1000, 'application/pdf'),
            'order' => 1,
        ];
        $response = $this->actingAs($this->adminUser)->putJson(route('cms.slideshow-item.update', ['slideshow' => $slideItem['slideshow_id'], 'item' => $slideItem['id']]), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}
