<?php

namespace Tests\Feature\Cms;

use App\Models\Cms\FileManagement;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CRUDTestCase;

class FileManagerTest extends CRUDTestCase
{
    private static $res;

    public function setUp(): void
    {
        parent::setUp();
        $this->setBaseModel(FileManagement::class);
        $this->setBaseRoute('cms.file-manager');
        $this->setBaseUser(User::where('email', 'admin@arkatama.test')->first());
    }

    public function test_must_authenticated_to_access_page(): void
    {
        $this->testAccess(route: $this->base_route.'.index', method: 'get', user: null, status: 302);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $this->testAccess(route: $this->base_route.'.index', method: 'get', user: $this->base_user, status: 200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $this->testShowDatatable();
    }

    public function test_can_create_file(): void
    {
        $response = $this->testCreate([
            'file' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
            'keterangan' => 'File Description',
        ]);
        Storage::assertExists($response[1]->file);
        self::$res = $response[1];
    }

    public function test_cannot_create_file_with_invalid_file_type(): void
    {
        $this->request(route($this->base_route.'.store'), 'POST', $this->base_user, [
            'file' => UploadedFile::fake()->create('file.txt', 1000, 'text/plain'),
            'keterangan' => 'File Description',
        ], after: function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('file');
        });
    }

    public function test_create_validation_when_required_fields_are_empty(): void
    {
        $this->request(route($this->base_route.'.store'), 'POST', $this->base_user, [
            'file' => null,
            'keterangan' => null,
        ], after: function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('file');
            $response->assertJsonValidationErrors('keterangan');
        });
    }

    public function test_update_validation_when_required_fields_are_empty(): void
    {
        $this->request(route($this->base_route.'.update', self::$res->id), 'PUT', $this->base_user, [
        ], after: function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('file');
            $response->assertJsonValidationErrors('keterangan');
        });
    }

    public function test_cannot_create_file_with_invalid_file_size(): void
    {
        $this->request(route($this->base_route.'.store'), 'POST', $this->base_user, [
            'file' => UploadedFile::fake()->create('file.pdf', 1000000, 'application/pdf'),
            'keterangan' => 'File Description',
        ], after: function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('file');
        });
    }

    public function test_can_update_file(): void
    {

        $this->testUpdate(self::$res, [
            'file' => UploadedFile::fake()->create('file_edited.pdf', 1000, 'application/pdf'),
            'keterangan' => 'File Description',
        ], onSuccess: function ($response) {
            $latest = FileManagement::latest('updated_at')->first();
            Storage::assertExists($latest->file);
            $this->assertDatabaseMissing($latest->getTable(), self::$res->toArray());
            $this->assertDatabaseHas($latest->getTable(), collect($latest)->except(['created_at', 'updated_at'])->toArray());
            Storage::assertMissing(self::$res->file);
        });
    }

    public function test_cannot_update_file_with_invalid_file_type(): void
    {
        $this->request(route($this->base_route.'.update', self::$res->id), 'PUT', $this->base_user, [
            'file' => UploadedFile::fake()->create('file_edited.txt', 1000, 'text/plain'),
            'keterangan' => 'File Description',
        ], after: function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('file');
        });
    }

    public function test_cannot_update_file_with_invalid_file_size(): void
    {
        $this->request(route($this->base_route.'.update', self::$res->id), 'PUT', $this->base_user, [
            'file' => UploadedFile::fake()->create('file.pdf', 1000000, 'application/pdf'),
            'keterangan' => 'File Description',
        ], after: function ($response) {
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('file');
        });
    }

    public function test_can_delete_file(): void
    {
        $this->testDelete(self::$res);
    }
}
