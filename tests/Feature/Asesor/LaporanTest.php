<?php

namespace Tests\Feature\Asesor;

use App\Models\Asesor\Penilaian;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\AsesorPeserta;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    private $asesor;

    private $route;

    private $formulir;

    private $penilaian;

    public $defaultPayload;

    public function setUp(): void
    {
        parent::setUp();

        $pembayaran = Pembayaran::factory()->create();
        $this->formulir = $pembayaran->register->formulir;

        $matakuliah = Matakuliah::first();

        $this->asesor = User::where('email', 'asesor@arkatama.test')->first();

        $matkul_asesor = MatakuliahAsesor::create(
            [
                'matkul_id' => $matakuliah->id,
                'asesor_id' => $this->asesor->asAsesorInstance->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        );

        AsesorPeserta::create(
            [
                'matkul_asesor_id' => $matkul_asesor->id,
                'formulir_id' => $this->formulir->id,
            ]
        );

        $this->actingAs($this->formulir->register->user);

        $this->penilaian = Penilaian::create([
            'status_kelulusan' => 'L',
            'rekomendasi' => 'Direkomendasikan',
            'nilai' => 'B',
            'matkul_id' => $matakuliah->id,
            'formulir_id' => $this->formulir->id,
            'tingkat_kemampuan' => 'Baik',
        ]);

        $this->penilaian->detail_penilaian()
            ->create([
                'status_kelulusan' => 'L',
                'keterangan' => 'Keterangan',
                'matkul_id' => $this->penilaian->matkul_id,
                'nilai_angka' => 80,
                'nilai_huruf' => $this->penilaian->nilai,
                'matkul_asesor_id' => $matkul_asesor->id,
            ], );

        $this->route = 'asesor.laporan';
    }

    public function test_must_authenticated_to_access_page()
    {

        $response = $this->get(route($this->route.'.index'));
        $response->assertStatus(403);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->actingAs($this->asesor)->get(route($this->route.'.index'));
        $response->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $response = $this->actingAs($this->asesor)
            ->getJson(route($this->route.'.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);
        $response->assertJsonFragment([
            'id' => $this->formulir->id,
        ]);
        $response->assertSeeText($this->formulir->nama_lengkap);
    }

    public function test_show_by_formulir(): void
    {
        $response = $this->actingAs($this->asesor)
            ->getJson(route($this->route.'.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);
        $response->assertJsonFragment([
            'id' => $this->formulir->id,
        ]);
    }

    public function test_print_laporan(): void
    {
        $response = $this->actingAs($this->asesor)
            ->post(route($this->route.'.print', ['formulir' => $this->formulir->id]));
        $response->assertSuccessful();
        $this->assertEquals('text/html; charset=UTF-8', $response->headers->get('content-type'));
    }
}
