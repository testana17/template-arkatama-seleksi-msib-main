<?php

namespace Tests\Feature\Kompetensi;

use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Formulir;
use App\Models\User;
use Tests\SimpleTest;

class KompetensiTest extends SimpleTest
{
    protected $route = 'kompetensi.';

    protected $routeParam = 'formulir';

    protected $formulir;

    public function setupFormulir(): void
    {
        $pembayaran = Pembayaran::factory()->create();
        $this->formulir = $pembayaran->register->formulir;
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->actingAs($this->user);
        $this->setupFormulir();
    }

    public function test_must_authenticated_to_access_page(): void
    {
        auth()->logout();
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(302);
    }

    public function test_index_page_returns_successful_response_when_user_is_authenticated(): void
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

    public function test_can_show_formulir_tipe_a_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'formulir-tipe-a', [$this->routeParam => $this->formulir->id]));
        $response->assertStatus(200);
    }

    public function test_can_show_informasi_pembayaran_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'informasi-pembayaran', [$this->routeParam => $this->formulir->id]));
        $response->assertStatus(200);
    }

    public function test_can_show_berkas_persyaratan_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'berkas-persyaratan', [$this->routeParam => $this->formulir->id]));
        $response->assertStatus(200);
    }

    public function test_can_show_evaluasi_diri_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'evaluasi-diri', [$this->routeParam => $this->formulir->id]));
        $response->assertStatus(200);
    }

    public function test_can_show_cv_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'cv', [$this->routeParam => $this->formulir->id]));
        $response->assertStatus(200);
    }

    public function test_can_show_kompetensi_akhir_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'kompetensi-akhir', [$this->routeParam => $this->formulir->id]));
        $response->assertStatus(200);
    }

    public function test_datatable_berkas_persyaratan_on_berkas_persyaratan_page_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'berkas-persyaratan', [$this->routeParam => $this->formulir->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_evaluasi_diri_on_evaluasi_diri_page_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'evaluasi-diri', [$this->routeParam => $this->formulir->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_riwayat_organisasi_on_cv_page_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'riwayat-organisasi', [$this->routeParam => $this->formulir->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_riwayat_pekerjaan_on_cv_page_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'riwayat-pekerjaan', [$this->routeParam => $this->formulir->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_riwayat_pelatihan_on_cv_page_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'riwayat-pelatihan', [$this->routeParam => $this->formulir->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_riwayat_pendidikan_on_cv_page_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'riwayat-pendidikan', [$this->routeParam => $this->formulir->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_riwayat_penghargaan_on_cv_page_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'riwayat-penghargaan', [$this->routeParam => $this->formulir->id]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_can_update_status_kelulusan_if_status_kelulusan_field_is_null_on_database(): void
    {
        Formulir::where('id', $this->formulir->id)->update(['status_kelulusan' => null]);
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'status-kelulusan', [$this->routeParam => $this->formulir->id]), [
                '_token' => csrf_token(),
            ]);
        $response->assertStatus(200);
    }

    public function test_block_update_status_kelulusan_if_status_kelulusan_field_is_lulus_on_database(): void
    {
        Formulir::where('id', $this->formulir->id)->update(['status_kelulusan' => 'LULUS']);
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'status-kelulusan', [$this->routeParam => $this->formulir->id]), [
                '_token' => csrf_token(),
            ]);
        $response->assertStatus(400);
    }

    public function test_block_update_status_kelulusan_if_status_kelulusan_field_is_tidak_lulus_on_database(): void
    {
        Formulir::where('id', $this->formulir->id)->update(['status_kelulusan' => 'TIDAK_LULUS']);
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'status-kelulusan', [$this->routeParam => $this->formulir->id]), [
                '_token' => csrf_token(),
            ]);
        $response->assertStatus(400);
    }
}
