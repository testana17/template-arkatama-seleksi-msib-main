<?php

namespace Tests\Feature\RPL;

use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class SyaratPendaftaranTest extends CRUDTestCase
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
            'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
            'persyaratan' => 'Tidak Buta Warna',
            'keterangan' => 'Surat Keterangan dari Dokter',
        ];
        $this->route = 'rpl.syarat-pendaftaran.';
        $this->table = 'ref_persyaratan';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('rpl.syarat-pendaftaran');
        $this->setBaseModel(SyaratPendaftaran::class);
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

    public function test_can_create_syarat_pendaftaran(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_syarat_pendaftaran()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $updatePayload = array_merge($this->defaultPayload, [
            'persyaratan' => 'Surat Dokter tidak buta warna',
            'keterangan' => 'Surat Keterangan dari Dokter yang tidak buta warna',
        ]);
        $this->testUpdate(model: $model, attributes: $updatePayload);
    }

    public function test_can_delete_syarat_pendaftaran()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_syarat_pendaftaran()
    {
        $model = $this->test_can_delete_syarat_pendaftaran();
        $this->testRestore(model: $model);
    }

    public function test_create_validation_prodi_id_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['prodi_id']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_prodi_id_exists()
    {
        $payload = $this->defaultPayload;
        $payload['prodi_id'] = 999;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_persyaratan_required()
    {
        $payload = $this->defaultPayload;
        unset($payload['persyaratan']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_create_validation_persyaratan_string()
    {
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = 123;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_create_validation_persyaratan_min()
    {
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = 'aa';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_create_validation_persyaratan_max()
    {
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = str_repeat('a', 256);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_create_validation_persyaratan_alpha_num_spaces_with_alphabet_and_symbol()
    {
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = 'aa@';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_create_validation_keterangan_string()
    {
        $payload = $this->defaultPayload;
        $payload['keterangan'] = 123;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('keterangan');
    }

    public function test_create_validation_keterangan_alpha_num_spaces_with_alphabet_and_symbol()
    {
        $payload = $this->defaultPayload;
        $payload['keterangan'] = 'aa@';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('keterangan');
    }

    public function test_create_validation_dokumen_template_file()
    {
        $payload = $this->defaultPayload;
        $payload['dokumen_template'] = UploadedFile::fake()->create('file.exe', 2, 'application/exe');
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dokumen_template');
    }

    public function test_create_validation_dokumen_template_mimes()
    {
        $payload = $this->defaultPayload;
        $payload['dokumen_template'] = UploadedFile::fake()->create('file.exe', 1000, 'application/exe');
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dokumen_template');
    }

    public function test_create_validation_dokumen_template_max()
    {
        $payload = $this->defaultPayload;
        $payload['dokumen_template'] = UploadedFile::fake()->create('file.pdf', 3000, 'application/pdf');
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dokumen_template');
    }

    public function test_update_validation_prodi_id_required()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        unset($payload['prodi_id']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_prodi_id_exists()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['prodi_id'] = 999;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_persyaratan_required()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        unset($payload['persyaratan']);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_update_validation_persyaratan_string()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = 123;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_update_validation_persyaratan_min()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = 'aa';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_update_validation_persyaratan_max()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = str_repeat('a', 256);
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_update_validation_persyaratan_alpha_num_spaces_with_alphabet_and_symbol()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['persyaratan'] = 'aa@';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('persyaratan');
    }

    public function test_update_validation_keterangan_string()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['keterangan'] = 123;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_alpha_num_spaces_with_alphabet_and_symbol()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['keterangan'] = 'aa@';
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_dokumen_template_file()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['dokumen_template'] = UploadedFile::fake()->create('file.exe', 2, 'application/exe');
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dokumen_template');
    }

    public function test_update_validation_dokumen_template_mimes()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['dokumen_template'] = UploadedFile::fake()->create('file.exe', 1000, 'application/exe');
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dokumen_template');
    }

    public function test_update_validation_dokumen_template_max()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['dokumen_template'] = UploadedFile::fake()->create('file.pdf', 3000, 'application/pdf');
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dokumen_template');
    }

    public function test_update_validation_dokumen_template_nullable()
    {
        $model = $this->test_can_create_syarat_pendaftaran();
        $payload = $this->defaultPayload;
        $payload['dokumen_template'] = null;
        $response = $this->actingAs($this->adminUser)->putJson(route($this->route.'update', $model->id), $payload);
        $response->assertStatus(200);
    }
}
