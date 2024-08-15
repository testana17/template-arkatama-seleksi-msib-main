<?php

namespace Tests\Feature\RPL;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Timeline;
use App\Models\Rpl\ProdiPilihan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class ProdiPilihanTest extends CRUDTestCase
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

        $unsetProdiId = ProgramStudi::whereNotIn('id', ProdiPilihan::pluck('prodi_id'))->first()?->id;
        if (! $unsetProdiId) {
            //prodi pilihan when doesnt havee relationship
            $deletedProdiPilihan = ProdiPilihan::whereDoesntHave('register')->first();
            $unsetProdiId = $deletedProdiPilihan->prodi_id;
            $deletedProdiPilihan->forceDelete();
        }
        $currentTahunAjaran = TahunAjaran::getCurrent();
        $this->defaultPayload = [
            'prodi_id' => $unsetProdiId,
            'tahun_ajaran_id' => $currentTahunAjaran['id'],
            'tanggal_mulai_pendaftaran' => Timeline::where('tahun_ajaran_id', $currentTahunAjaran['id'])
                ->value('tanggal_mulai_pendaftaran'),
            'tanggal_selesai_pendaftaran' => Timeline::where('tahun_ajaran_id', $currentTahunAjaran['id'])
                ->value('tanggal_selesai_pendaftaran'),
            'tanggal_mulai_administrasi' => Timeline::where('tahun_ajaran_id', $currentTahunAjaran['id'])
                ->value('tanggal_mulai_administrasi'),
            'tanggal_selesai_administrasi' => Timeline::where('tahun_ajaran_id', $currentTahunAjaran['id'])
                ->value('tanggal_selesai_administrasi'),
            'tanggal_pengumuman' => '2024-08-01',
            'kuota_pendaftar' => 100,
        ];
        $this->route = 'pengaturan-rpl.prodi-pilihan.';
        $this->table = 'prodi_pilihan';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('pengaturan-rpl.prodi-pilihan');
        $this->setBaseModel(ProdiPilihan::class);
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

    public function test_can_create_prodi_pilihan(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_prodi_pilihan()
    {
        $model = $this->test_can_create_prodi_pilihan();
        $updatePayload = array_merge($this->defaultPayload, [
            'tanggal_mulai_pendaftaran' => '2024-08-01',
            'tanggal_selesai_pendaftaran' => '2024-08-02',
            'tanggal_mulai_administrasi' => '2024-08-03',
            'tanggal_selesai_administrasi' => '2024-08-04',
            'tanggal_pengumuman' => '2024-08-05',
            'kuota_pendaftar' => 200,
        ]);
        $response = $this->testUpdate(model: $model, attributes: $updatePayload);
    }

    public function test_can_delete_prodi_pilihan()
    {
        $model = $this->test_can_create_prodi_pilihan();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_prodi_pilihan()
    {
        $model = $this->test_can_delete_prodi_pilihan();
        $response = $this->testRestore(model: $model);
    }

    public function test_create_validation_prodi_id_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['prodi_id']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_prodi_id_exists(): void
    {
        $payload = $this->defaultPayload;
        $payload['prodi_id'] = 999;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_prodi_id_unique(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['prodi_id'] = $model->prodi_id;
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('prodi_id');
    }

    public function test_create_validation_tanggal_mulai_pendaftaran_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['tanggal_mulai_pendaftaran']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_mulai_pendaftaran');
    }

    public function test_create_validation_tanggal_mulai_pendaftaran_date(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = 'not-a-date';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_mulai_pendaftaran');
    }

    public function test_create_validation_tanggal_mulai_pendaftaran_before_tanggal_selesai_pendaftaran(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-08-01';
        $payload['tanggal_selesai_pendaftaran'] = '2024-07-31';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_mulai_pendaftaran');
    }

    public function test_create_validation_tanggal_selesai_pendaftaran_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['tanggal_selesai_pendaftaran']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_create_validation_tanggal_selesai_pendaftaran_date(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_selesai_pendaftaran'] = 'not-a-date';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_create_validation_tanggal_selesai_pendaftaran_after_tanggal_mulai_pendaftaran(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-08-01';
        $payload['tanggal_selesai_pendaftaran'] = '2024-07-31';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_create_validation_tanggal_selesai_pendaftaran_before_or_equal_tanggal_selesai_administrasi(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-07-29';
        $payload['tanggal_selesai_pendaftaran'] = '2024-08-02';
        $payload['tanggal_mulai_administrasi'] = '2024-07-29';
        $payload['tanggal_selesai_administrasi'] = '2024-08-01';
        $payload['tanggal_pengumuman'] = '2024-08-03';
        $response = $this->actingAs($this->adminUser)
            ->postJson(route($this->route.'store'), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_create_validation_tanggal_mulai_administrasi_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['tanggal_mulai_administrasi']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_mulai_administrasi');
    }

    public function test_create_validation_tanggal_mulai_administrasi_date(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_administrasi'] = 'not-a-date';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_mulai_administrasi');
    }

    public function test_create_validation_tanggal_mulai_administrasi_before_tanggal_selesai_administrasi(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_administrasi'] = '2024-08-01';
        $payload['tanggal_selesai_administrasi'] = '2024-07-31';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_mulai_administrasi');
    }

    public function test_create_validation_tanggal_selesai_administrasi_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['tanggal_selesai_administrasi']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_create_validation_tanggal_selesai_administrasi_date(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_selesai_administrasi'] = 'not-a-date';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_create_validation_tanggal_selesai_administrasi_after_tanggal_mulai_administrasi(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_administrasi'] = '2024-08-01';
        $payload['tanggal_selesai_administrasi'] = '2024-07-31';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_create_validation_tanggal_selesai_administrasi_before_or_equal_tanggal_pengumuman(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-08-03';
        $payload['tanggal_selesai_pendaftaran'] = '2024-08-04';
        $payload['tanggal_mulai_administrasi'] = '2024-08-03';
        $payload['tanggal_selesai_administrasi'] = '2024-08-06';
        $payload['tanggal_pengumuman'] = '2024-08-05';
        $response = $this->actingAs($this->adminUser)
            ->postJson(route($this->route.'store'), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_create_validation_tanggal_pengumuman_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['tanggal_pengumuman']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_pengumuman');
    }

    public function test_create_validation_tanggal_pengumuman_date(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_pengumuman'] = 'not-a-date';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tanggal_pengumuman');
    }

    public function test_create_validation_tanggal_pengumuman_after_or_equal_tanggal_selesai_administrasi(): void
    {
        $payload = $this->defaultPayload;
        $payload['tanggal_selesai_administrasi'] = '2024-08-01';
        $payload['tanggal_pengumuman'] = '2024-07-31';
        $payload['tanggal_mulai_administrasi'] = '2024-07-30';
        $payload['tanggal_selesai_pendaftaran'] = '2024-07-29';
        $payload['tanggal_mulai_pendaftaran'] = '2024-07-28';
        $response = $this->actingAs($this->adminUser)
            ->postJson(route($this->route.'store'), $payload)
            ->assertJsonValidationErrors('tanggal_pengumuman');
    }

    public function test_create_validation_kuota_pendaftar_required(): void
    {
        $payload = $this->defaultPayload;
        unset($payload['kuota_pendaftar']);
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kuota_pendaftar');
    }

    public function test_create_validation_kuota_pendaftar_numeric(): void
    {
        $payload = $this->defaultPayload;
        $payload['kuota_pendaftar'] = 'not-a-number';
        $response = $this->actingAs($this->adminUser)->postJson(route($this->route.'store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('kuota_pendaftar');
    }

    public function test_update_validation_prodi_id_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['prodi_id']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_prodi_id_exists(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['prodi_id'] = 999;

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('prodi_id');
    }

    public function test_update_validation_tahun_ajaran_id_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['tahun_ajaran_id']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tahun_ajaran_id');
    }

    public function test_update_validation_tahun_ajaran_id_exists(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tahun_ajaran_id'] = 999;

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tahun_ajaran_id');
    }

    public function test_update_validation_tanggal_mulai_pendaftaran_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['tanggal_mulai_pendaftaran']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_mulai_pendaftaran');
    }

    public function test_update_validation_tanggal_mulai_pendaftaran_date(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = 'not-a-date';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_mulai_pendaftaran');
    }

    public function test_update_validation_tanggal_mulai_pendaftaran_before_tanggal_selesai_pendaftaran(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-08-01';
        $payload['tanggal_selesai_pendaftaran'] = '2024-07-31';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_mulai_pendaftaran');
    }

    public function test_update_validation_tanggal_selesai_pendaftaran_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['tanggal_selesai_pendaftaran']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_update_validation_tanggal_selesai_pendaftaran_date(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_selesai_pendaftaran'] = 'not-a-date';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_update_validation_tanggal_selesai_pendaftaran_after_tanggal_mulai_pendaftaran(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-08-01';
        $payload['tanggal_selesai_pendaftaran'] = '2024-07-31';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_update_validation_tanggal_selesai_pendaftaran_before_or_equal_tanggal_selesai_administrasi(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-07-29';
        $payload['tanggal_selesai_pendaftaran'] = '2024-08-02';
        $payload['tanggal_mulai_administrasi'] = '2024-07-29';
        $payload['tanggal_selesai_administrasi'] = '2024-08-01';
        $payload['tanggal_pengumuman'] = '2024-08-03';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_pendaftaran');
    }

    public function test_update_validation_tanggal_mulai_administrasi_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['tanggal_mulai_administrasi']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_mulai_administrasi');
    }

    public function test_update_validation_tanggal_mulai_administrasi_date(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_administrasi'] = 'not-a-date';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_mulai_administrasi');
    }

    public function test_update_validation_tanggal_mulai_administrasi_before_tanggal_selesai_administrasi(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_administrasi'] = '2024-08-01';
        $payload['tanggal_selesai_administrasi'] = '2024-07-31';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_mulai_administrasi');
    }

    public function test_update_validation_tanggal_selesai_administrasi_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['tanggal_selesai_administrasi']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_update_validation_tanggal_selesai_administrasi_date(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_selesai_administrasi'] = 'not-a-date';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_update_validation_tanggal_selesai_administrasi_after_tanggal_mulai_administrasi(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_administrasi'] = '2024-08-01';
        $payload['tanggal_selesai_administrasi'] = '2024-07-31';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_update_validation_tanggal_selesai_administrasi_before_or_equal_tanggal_pengumuman(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_mulai_pendaftaran'] = '2024-08-03';
        $payload['tanggal_selesai_pendaftaran'] = '2024-08-04';
        $payload['tanggal_mulai_administrasi'] = '2024-08-03';
        $payload['tanggal_selesai_administrasi'] = '2024-08-06';
        $payload['tanggal_pengumuman'] = '2024-08-05';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_selesai_administrasi');
    }

    public function test_update_validation_tanggal_pengumuman_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['tanggal_pengumuman']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_pengumuman');
    }

    public function test_update_validation_tanggal_pengumuman_date(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_pengumuman'] = 'not-a-date';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_pengumuman');
    }

    public function test_update_validation_tanggal_pengumuman_after_or_equal_tanggal_selesai_administrasi(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['tanggal_selesai_administrasi'] = '2024-08-01';
        $payload['tanggal_pengumuman'] = '2024-07-31';
        $payload['tanggal_mulai_administrasi'] = '2024-07-30';
        $payload['tanggal_selesai_pendaftaran'] = '2024-07-29';
        $payload['tanggal_mulai_pendaftaran'] = '2024-07-28';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('tanggal_pengumuman');
    }

    public function test_update_validation_kuota_pendaftar_required(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        unset($payload['kuota_pendaftar']);

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('kuota_pendaftar');
    }

    public function test_update_validation_kuota_pendaftar_numeric(): void
    {
        $model = $this->test_can_create_prodi_pilihan();
        $payload = $this->defaultPayload;
        $payload['kuota_pendaftar'] = 'not-a-number';

        $this->actingAs($this->adminUser)
            ->putJson(route($this->route.'update', $model->id), $payload)
            ->assertJsonValidationErrors('kuota_pendaftar');
    }
}
