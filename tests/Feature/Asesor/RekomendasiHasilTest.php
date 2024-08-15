<?php

namespace Tests\Feature\Asesor;

use App\Models\Asesor\Penilaian;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\AsesorPeserta;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\CreatesApplication;
use Tests\TestCase;

class RekomendasiHasilTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    private $asesor1;

    private $asesor2;

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

        $this->asesor1 = User::where('email', 'asesor@arkatama.test')->first();
        $this->asesor2 = User::where('email', 'asesor2@arkatama.test')->first();

        $matkul_asesor = MatakuliahAsesor::create(
            [
                'matkul_id' => $matakuliah->id,
                'asesor_id' => $this->asesor1->asAsesorInstance->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        );

        $matkul_asesor2 = MatakuliahAsesor::create(
            [
                'matkul_id' => $matakuliah->id,
                'asesor_id' => $this->asesor2->asAsesorInstance->id,
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

        AsesorPeserta::create(
            [
                'matkul_asesor_id' => $matkul_asesor2->id,
                'formulir_id' => $this->formulir->id,
            ]
        );

        $this->actingAs($this->formulir->register->user);

        $this->penilaian = Penilaian::create([
            'nilai' => 'B',
            'matkul_id' => $matakuliah->id,
            'formulir_id' => $this->formulir->id,
            'tingkat_kemampuan' => 'Baik',
            'is_valid' => '1',
        ]);

        $this->penilaian->detail_penilaian()
            ->createMany([
                [
                    'matkul_id' => $this->penilaian->matkul_id,
                    'nilai_angka' => 80,
                    'nilai_huruf' => $this->penilaian->nilai,
                    'matkul_asesor_id' => $matkul_asesor->id,
                ],
                [
                    'matkul_id' => $this->penilaian->matkul_id,
                    'nilai_angka' => 40,
                    'nilai_huruf' => $this->penilaian->nilai,
                    'matkul_asesor_id' => $matkul_asesor2->id,
                ],
            ]);

        $this->route = 'asesor.rekomendasi-hasil';
    }

    public function test_must_authenticated_to_access_page()
    {

        $response = $this->get(route($this->route));
        $response->assertStatus(403);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->actingAs($this->asesor1)->get(route($this->route));
        $response->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $response = $this->actingAs($this->asesor1)
            ->getJson(route($this->route), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);
    }

    public function test_can_confirm_rekomendasi_hasil_tidak_direkomendasikan(): void
    {
        $route = route($this->route.'.update-invalid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor1)->putJson($route);
        $response->assertStatus(200);
        $dataPenilaian = collect(array_merge(collect($this->penilaian)->except(['created_at', 'updated_at'])->toArray(), [
            'rekomendasi' => 'Tidak Direkomendasikan',
            'status_kelulusan' => 'TL',
        ]))->except(['created_at', 'updated_at'])->toArray();
        $this->assertDatabaseHas('penilaian', $dataPenilaian);
        $this->assertDatabaseHas('penilaian_detail', [
            'penilaian_id' => $this->penilaian->id,
            'status_kelulusan' => 'TL',
        ]);

        $countInvalid = $this->penilaian->detail_penilaian()->where('status_kelulusan', 'TL')->count();
        $this->assertEquals(1, $countInvalid);

        $countNull = $this->penilaian->detail_penilaian()->where('status_kelulusan', null)->count();
        $this->assertEquals(1, $countNull);
    }

    public function test_can_confirm_rekomendasi_hasil_direkomendasikan(): void
    {
        $route = route($this->route.'.update-valid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor1)->putJson($route);
        $response->assertStatus(200);

        $dataPenilaian = collect(array_merge(collect($this->penilaian)->except(['created_at', 'updated_at'])->toArray(), [
            'rekomendasi' => 'Direkomendasikan',
            'status_kelulusan' => 'L',
        ]))->except(['created_at', 'updated_at'])->toArray();
        $this->assertDatabaseHas('penilaian', $dataPenilaian);
        $this->assertDatabaseHas('penilaian_detail', [
            'penilaian_id' => $this->penilaian->id,
            'status_kelulusan' => 'L',
        ]);

        $countValid = $this->penilaian->detail_penilaian()->where('status_kelulusan', 'L')->count();
        $this->assertEquals(1, $countValid);

        $countNull = $this->penilaian->detail_penilaian()->where('status_kelulusan', null)->count();
        $this->assertEquals(1, $countNull);
    }

    public function test_can_confirm_rekomendasi_one_valid_one_invalid_is_valid(): void
    {
        $route = route($this->route.'.update-valid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor1)->putJson($route);
        $response->assertStatus(200);

        Auth::logout();

        $route = route($this->route.'.update-invalid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor2)->putJson($route);
        $response->assertStatus(200);

        $dataPenilaian = array_merge(collect($this->penilaian)->except(['created_at', 'updated_at'])->toArray(), [
            'rekomendasi' => 'Direkomendasikan',
            'status_kelulusan' => 'L',
        ]);
        $this->assertDatabaseHas('penilaian', $dataPenilaian);
        $this->assertDatabaseHas('penilaian_detail', [
            'penilaian_id' => $this->penilaian->id,
        ]);
        $countValid = $this->penilaian->detail_penilaian()->where('status_kelulusan', 'L')->count();
        $this->assertEquals(1, $countValid);
        $countInvalid = $this->penilaian->detail_penilaian()->where('status_kelulusan', 'TL')->count();
        $this->assertEquals(1, $countInvalid);
    }

    public function test_can_confirm_rekomendasi_one_valid_one_valid_is_valid(): void
    {
        $route = route($this->route.'.update-valid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor1)->putJson($route);
        $response->assertStatus(200);

        Auth::logout();

        $route = route($this->route.'.update-valid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor2)->putJson($route);
        $response->assertStatus(200);

        $dataPenilaian = array_merge(collect($this->penilaian)->except(['created_at', 'updated_at'])->toArray(), [
            'rekomendasi' => 'Direkomendasikan',
            'status_kelulusan' => 'L',
        ]);
        $this->assertDatabaseHas('penilaian', $dataPenilaian);
        $this->assertDatabaseHas('penilaian_detail', [
            'penilaian_id' => $this->penilaian->id,
        ]);

        $countValid = $this->penilaian->detail_penilaian()->where('status_kelulusan', 'L')->count();
        $this->assertEquals(2, $countValid);
    }

    public function test_can_confirm_rekomendasi_one_invalid_one_invalid_is_invalid(): void
    {
        $route = route($this->route.'.update-invalid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor1)->putJson($route);
        $response->assertStatus(200);

        Auth::logout();

        $route = route($this->route.'.update-invalid', ['formulir' => $this->formulir->id]);
        $response = $this->actingAs($this->asesor2)->putJson($route);
        $response->assertStatus(200);

        $dataPenilaian = array_merge(collect($this->penilaian)->except(['created_at', 'updated_at'])->toArray(), [
            'rekomendasi' => 'Tidak Direkomendasikan',
            'status_kelulusan' => 'TL',
        ]);
        $this->assertDatabaseHas('penilaian', $dataPenilaian);
        $this->assertDatabaseHas('penilaian_detail', [
            'penilaian_id' => $this->penilaian->id,
            'status_kelulusan' => 'TL',
        ]);

        $countInvalid = $this->penilaian->detail_penilaian()->where('status_kelulusan', 'TL')->count();
        $this->assertEquals(2, $countInvalid);
    }
}
