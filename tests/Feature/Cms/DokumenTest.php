<?php

namespace Tests\Feature\Cms;

use App\Models\Cms\Dokumen;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\SimpleTest;

class DokumenTest extends SimpleTest
{
    protected $table = 'dokumen';

    protected $model = Dokumen::class;

    protected $route = 'cms.document.';

    protected $routeParam = 'document';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->actingAs($this->user);
        $this->payload = [
            'nama' => 'Dokumen Test',
            'keterangan' => 'Keterangan Dokumen Test',
            'file' => UploadedFile::fake()
                ->create('dokumen-test.pdf', 100, 'application/pdf'),
        ];

        Dokumen::create($this->payload);
    }

    public function test_must_authenticated_to_access_page(): void
    {
        auth()->logout();
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(302);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route($this->route.'index'));
        $response->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_entry_histories_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'histori'), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);
        $response->assertJsonStructure(['data']);
    }

    public function test_can_create_dokumen_with_valid_payload(): void
    {
        $this->testCreate(assertDbHasExcept: ['file']);
    }

    public function test_insert_validation_nama_is_required(): void
    {
        $this->testCreate([
            'nama' => null,
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_insert_validation_nama_min_3_characters(): void
    {
        $this->testCreate([
            'nama' => 'ab',
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_insert_validation_nama_max_255_characters(): void
    {
        $this->testCreate([
            'nama' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_insert_validation_nama_can_contains_combination_of_alphabets_spaces_and_symbols(): void
    {
        $this->testCreate([
            'nama' => 'Dokumen Test 123.pdf',
        ], assertDbHasExcept: ['file']);
    }

    public function test_insert_validation_nama_cannot_contains_only_numbers(): void
    {
        $this->testCreate([
            'nama' => '123',
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_insert_validation_nama_cannot_contains_only_symbols(): void
    {
        $this->testCreate([
            'nama' => '!!!',
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_insert_validation_keterangan_is_required(): void
    {
        $this->testCreate([
            'keterangan' => null,
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_min_3_characters(): void
    {
        $this->testCreate([
            'keterangan' => 'ab',
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_max_255_characters(): void
    {
        $this->testCreate([
            'keterangan' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_can_contains_combination_of_alphabets_spaces_and_symbols(): void
    {
        $this->testCreate([
            'keterangan' => 'Dokumen Test 123.pdf',
        ], assertDbHasExcept: ['file']);
    }

    public function test_insert_validation_keterangan_cannot_contains_only_numbers(): void
    {
        $this->testCreate([
            'keterangan' => '123',
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_cannot_contains_only_symbols(): void
    {
        $this->testCreate([
            'keterangan' => '!!!',
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_file_is_required(): void
    {
        $this->testCreate([
            'file' => null,
        ], 422)->assertJsonValidationErrors('file');
    }

    public function test_insert_validation_file_must_be_a_file(): void
    {
        $this->testCreate([
            'file' => 'abc',
        ], 422)->assertJsonValidationErrors('file');
    }

    public function test_can_update_dokumen_with_valid_payload(): void
    {
        $this->testUpdate(changePayload: [
            'nama' => 'Dokumen Test Updated',
            'file' => 'abc',
        ], assertDbHasExcept: ['file']);
    }

    public function test_update_validation_nama_is_required(): void
    {
        $this->testUpdate([
            'nama' => null,
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_update_validation_nama_min_3_characters(): void
    {
        $this->testUpdate([
            'nama' => 'ab',
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_update_validation_nama_max_255_characters(): void
    {
        $this->testUpdate([
            'nama' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_update_validation_nama_can_contains_combination_of_alphabets_spaces_and_symbols(): void
    {
        $this->testUpdate([
            'nama' => 'Dokumen Test 123.pdf',
        ], assertDbHasExcept: ['file']);
    }

    public function test_update_validation_nama_cannot_contains_only_numbers(): void
    {
        $this->testUpdate([
            'nama' => '123',
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_update_validation_nama_cannot_contains_only_symbols(): void
    {
        $this->testUpdate([
            'nama' => '!!!',
        ], 422)->assertJsonValidationErrors('nama');
    }

    public function test_update_validation_keterangan_is_required(): void
    {
        $this->testUpdate([
            'keterangan' => null,
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_min_3_characters(): void
    {
        $this->testUpdate([
            'keterangan' => 'ab',
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_max_255_characters(): void
    {
        $this->testUpdate([
            'keterangan' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_can_contains_combination_of_alphabets_spaces_and_symbols(): void
    {
        $this->testUpdate([
            'keterangan' => 'Dokumen Test 123.pdf',
        ], assertDbHasExcept: ['file']);
    }

    public function test_update_validation_keterangan_cannot_contains_only_numbers(): void
    {
        $this->testUpdate([
            'keterangan' => '123',
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_cannot_contains_only_symbols(): void
    {
        $this->testUpdate([
            'keterangan' => '!!!',
        ], 422)->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_file_is_can_be_null(): void
    {
        $this->testUpdate([
            'file' => 'abc',
        ], assertDbHasExcept: ['file']);
    }

    public function test_can_delete_dokumen(): void
    {
        $this->testDelete(true);
    }

    public function test_can_restore_dokumen(): void
    {
        $this->testRestore();
    }
}
