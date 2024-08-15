<?php

namespace Tests\Feature\Cms;

use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Timeline;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class TimelineTest extends CRUDTestCase
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
            'tanggal_mulai_pendaftaran' => '2024-10-01',
            'tanggal_selesai_pendaftaran' => '2024-10-10',
            'tanggal_mulai_administrasi' => '2024-10-11',
            'tanggal_selesai_administrasi' => '2024-10-20',
            'tanggal_mulai_assesmen' => '2024-10-21',
            'tanggal_seleksi_evaluasi_diri' => '2024-10-30',
        ];
        Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->delete();
        $this->route = 'cms.timeline.';
        $this->table = 'timeline_aktivitas';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('cms.timeline');
        $this->setBaseModel(Timeline::class);
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

    public function test_can_create_timeline(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_timeline()
    {
        $model = $this->test_can_create_timeline();
        $updatePayload = array_merge($this->defaultPayload, [
            'tanggal_mulai_pendaftaran' => '2024-10-02',
            'tanggal_selesai_pendaftaran' => '2024-10-11',
            'tanggal_mulai_administrasi' => '2024-10-12',
            'tanggal_selesai_administrasi' => '2024-10-21',
            'tanggal_mulai_assesmen' => '2024-10-22',
            'tanggal_seleksi_evaluasi_diri' => '2024-10-31',
        ]);

        $response = $this->testUpdate(model: $model, attributes: $updatePayload);
    }

    public function test_can_delete_timeline_when_tahun_ajaran_is_current()
    {
        $model = $this->test_can_create_timeline();
        $response = $this->testDelete(model: $model, isSoftDeleting: true, status: 400);

        return $model;
    }

    public function test_can_delete_timeline_when_tahun_ajaran_is_not_current()
    {
        $model = $this->test_can_create_timeline();
        TahunAjaran::where('id', $model->tahun_ajaran_id)->update(['is_current' => '0']);
        $response = $this->testDelete(model: $model, isSoftDeleting: true, status: 200);

        return $model;
    }

    public function test_can_restore_timeline()
    {
        $model = $this->test_can_delete_timeline_when_tahun_ajaran_is_not_current();
        $this->model = $model;
        $response = $this->testRestore(model: $model);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_pendaftaran_required()
    {
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_selesai_pendaftaran()
    {
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_mulai_administrasi()
    {
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_selesai_administrasi()
    {
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_mulai_assesmen()
    {
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_seleksi_evaluasi_diri()
    {
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_pendaftaran_required()
    {
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_pendaftaran_after_today()
    {
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2021-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_mulai_administrasi()
    {
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_selesai_administrasi()
    {
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_mulai_assesmen()
    {
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_seleksi_evaluasi_diri()
    {
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_administrasi_required()
    {
        $this->defaultPayload['tanggal_mulai_administrasi'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_administrasi_after_today()
    {
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2021-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_administrasi_after_tanggal_mulai_pendaftaran()
    {
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-09-30';
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-01';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_administrasi_before_tanggal_selesai_administrasi()
    {
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_administrasi_before_tanggal_mulai_assesmen()
    {
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_administrasi_before_tanggal_seleksi_evaluasi_diri()
    {
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_administrasi_required()
    {
        $this->defaultPayload['tanggal_selesai_administrasi'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_administrasi_after_today()
    {
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2021-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_administrasi_after_tanggal_mulai_administrasi()
    {
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-09-30';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-01';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_administrasi_before_tanggal_mulai_assesmen()
    {
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_selesai_administrasi_before_tanggal_seleksi_evaluasi_diri()
    {
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_assesmen_required()
    {
        $this->defaultPayload['tanggal_mulai_assesmen'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_assesmen_after_today()
    {
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2021-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_assesmen_after_tanggal_mulai_pendaftaran()
    {
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-09-30';
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-01';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_mulai_assesmen_before_tanggal_seleksi_evaluasi_diri()
    {
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_seleksi_evaluasi_diri_required()
    {
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_seleksi_evaluasi_diri_after_today()
    {
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2021-09-30';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_mulai_pendaftaran()
    {
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-11';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_selesai_pendaftaran()
    {
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-11';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_mulai_administrasi()
    {
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-11';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_selesai_administrasi()
    {
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-11';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_mulai_assesmen()
    {
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-11';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_pendaftaran_required()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_selesai()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_mulai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_selesai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_mulai_assesmen()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_pendaftaran_before_tanggal_seleksi_evaluasi_diri()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-1';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_pendaftaran_required()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_pendaftaran_after_today()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2021-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_mulai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_selesai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_mulai_assesmen()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_pendaftaran_before_tanggal_seleksi_evaluasi_diri()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_administrasi_required()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_administrasi'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_administrasi_after_today()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2021-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_administrasi_after_tanggal_mulai_pendaftaran()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-09-30';
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-01';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_administrasi_before_tanggal_selesai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_administrasi_before_tanggal_mulai_assesmen()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_administrasi_before_tanggal_seleksi_evaluasi_diri()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_administrasi_required()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_administrasi'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_administrasi_after_today()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2021-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_administrasi_after_tanggal_mulai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-09-30';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-01';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_administrasi_before_tanggal_mulai_assesmen()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_selesai_administrasi_before_tanggal_seleksi_evaluasi_diri()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_assesmen_required()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_assesmen'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_assesmen_after_today()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2021-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_assesmen_after_tanggal_mulai_pendaftaran()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-09-30';
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-01';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_mulai_assesmen_before_tanggal_seleksi_evaluasi_diri()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-10';
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_seleksi_evaluasi_diri_required()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_seleksi_evaluasi_diri_after_today()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2021-09-30';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_mulai_pendaftaran()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_pendaftaran'] = '2024-10-11';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_selesai_pendaftaran()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_pendaftaran'] = '2024-10-11';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_mulai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_administrasi'] = '2024-10-11';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_selesai_administrasi()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_selesai_administrasi'] = '2024-10-11';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }

    public function test_validation_update_timeline_with_tanggal_seleksi_evaluasi_diri_after_tanggal_mulai_assesmen()
    {
        $model = $this->test_can_create_timeline();
        $this->defaultPayload['tanggal_seleksi_evaluasi_diri'] = '2024-10-10';
        $this->defaultPayload['tanggal_mulai_assesmen'] = '2024-10-11';
        $this->testUpdate($model, $this->defaultPayload, status: 422);
    }
}
