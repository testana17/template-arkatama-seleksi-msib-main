<?php

namespace Tests\Feature\RPL;

use App\Models\Rpl\Asesor;
use App\Models\Rpl\AsesorPeserta;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class AsesorPesertaTest extends CRUDTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    private $adminUser;

    private $route;

    public $defaultPayload;

    public $listMatkulAsesor = [];

    public $listFormulir = [];

    private $matakuliah;

    public function setUp(): void
    {
        parent::setUp();
        $adminUser = User::where('email', 'admin@arkatama.test')->first();
        $this->adminUser = $adminUser;

        $formulir1 = Formulir::factory()->create();
        $formulir2 = Formulir::factory()->create();

        $this->listFormulir = [$formulir1, $formulir2];

        $asesorAkademisi = Asesor::where('jenis_asesor', 'akademisi')->first();
        $this->matakuliah = Matakuliah::where('prodi_id', $formulir1->pilihan_prodi_id)->first();
        $matkulAsesorAkademisi = MatakuliahAsesor::create([
            'asesor_id' => $asesorAkademisi->id,
            'matkul_id' => $this->matakuliah->id,
            'created_by' => $adminUser->id,
            'updated_by' => $adminUser->id,
        ]);

        $asesorPraktisi = Asesor::where('jenis_asesor', 'praktisi')->first();
        $matkulAsesorPraktisi = MatakuliahAsesor::create([
            'asesor_id' => $asesorPraktisi->id,
            'matkul_id' => $this->matakuliah->id,
            'created_by' => $adminUser->id,
            'updated_by' => $adminUser->id,
        ]);

        $this->listMatkulAsesor = [
            'akademisi' => $matkulAsesorAkademisi,
            'praktisi' => $matkulAsesorPraktisi,
        ];

        $this->defaultPayload = [
            'list_asesor_peserta' => [
                [
                    'formulir_id' => $formulir1->id,
                    'asesor_akademisi' => $matkulAsesorAkademisi->id,
                ],
            ],
            'jenis_asesor' => 'akademisi',
            'tahun_ajaran_id' => $formulir1->tahun_ajaran_id,
            'matkul_asesor_id' => $matkulAsesorAkademisi->id,
        ];
        $this->route = 'pengaturan-rpl.asesor-peserta.';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('pengaturan-rpl.asesor-peserta');
        $this->setBaseModel(AsesorPeserta::class);
    }

    public function test_must_authenticated_to_access_page()
    {
        $this->testAccess(route: $this->route.'index', method: 'get', user: null, status: 302);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->testAccess(route: $this->route.'index', method: 'get', user: $this->adminUser, status: 200);

        $response->assertViewIs('pages.admin.rpl.asesor-peserta.index');
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $this->testShowDatatable(route: $this->route.'index');
    }

    public function test_datatable_entry_histories_must_be_returned(): void
    {
        $this->testShowDatatable(route: $this->route.'histori');
    }

    public function test_show_right_peserta_on_matakuliah_page(): void
    {
        $params = [
            'matakuliah_id' => $this->listMatkulAsesor['akademisi']->matkul_id,
            'tahun_ajaran_id' => $this->listFormulir[0]->tahun_ajaran_id,
        ];
        $response = $this->actingAs($this->adminUser)->get(route($this->route.'create', $params));
        $response->assertViewIs('pages.admin.rpl.asesor-peserta.create');
    }

    public function test_can_assign_one_asesor_to_one_peserta(): void
    {
        $response = $this->testCreate(attributes: $this->defaultPayload, status: 200);
        $this->assertDatabaseHas($this->base_model->getTable(), [
            'formulir_id' => $this->listFormulir[0]->id,
            'matkul_asesor_id' => $this->listMatkulAsesor['akademisi']->id,
        ]);
        $this->assertEquals(1, AsesorPeserta::where('formulir_id', $this->listFormulir[0]->id)
            ->where('matkul_asesor_id', $this->listMatkulAsesor['akademisi']->id)
            ->count());
    }

    public function test_can_assign_one_asesor_to_many_peserta(): void
    {
        $payload = $this->defaultPayload;
        array_push($payload['list_asesor_peserta'], [
            'formulir_id' => $this->listFormulir[1]->id,
            'asesor_akademisi' => $this->listMatkulAsesor['akademisi']->id,
        ]);

        $response = $this->testCreate(attributes: $payload, status: 200);

        $this->assertDatabaseHas($this->base_model->getTable(), [
            'formulir_id' => $this->listFormulir[0]->id,
            'matkul_asesor_id' => $this->listMatkulAsesor['akademisi']->id,
        ]);
        $this->assertDatabaseHas($this->base_model->getTable(), [
            'formulir_id' => $this->listFormulir[1]->id,
            'matkul_asesor_id' => $this->listMatkulAsesor['akademisi']->id,
        ]);
        $this->assertEquals(2, AsesorPeserta::where('matkul_asesor_id', $this->listMatkulAsesor['akademisi']->id)
            ->count());
    }

    public function test_can_remove_asesor_from_one_peserta(): void
    {
        $this->test_can_assign_one_asesor_to_one_peserta();
        $payload = $this->defaultPayload;
        $payload['matkul_asesor_id'] = '-1';

        $response = $this->testCreate(attributes: $payload, status: 200);
        $this->assertEquals(0, AsesorPeserta::where('formulir_id', $this->listFormulir[0]->id)
            ->where('matkul_asesor_id', $this->listMatkulAsesor['akademisi']->id)
            ->count());
    }

    public function test_can_remove_asesor_from_many_peserta(): void
    {
        $this->test_can_assign_one_asesor_to_many_peserta();
        $payload = $this->defaultPayload;
        array_push($payload['list_asesor_peserta'], [
            'formulir_id' => $this->listFormulir[1]->id,
            'asesor_akademisi' => $this->listMatkulAsesor['akademisi']->id,
        ]);
        $payload['matkul_asesor_id'] = '-1';

        $response = $this->testCreate(attributes: $payload, status: 200);
        $this->assertEquals(0, AsesorPeserta::where('matkul_asesor_id', $this->listMatkulAsesor['akademisi']->id)
            ->count());
    }
}
