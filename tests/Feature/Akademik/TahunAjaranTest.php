<?php

namespace Tests\Feature\Akademik;

use App\Models\Akademik\TahunAjaran;
use App\Models\User;
use Tests\SimpleTest;

class TahunAjaranTest extends SimpleTest
{
    protected $table = 'ref_tahun_ajaran';

    protected $model = TahunAjaran::class;

    protected $route = 'akademik.tahun-ajaran.';

    protected $routeParam = 'tahun_ajaran';

    protected $tahunAjaran;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->payload = [
            'tahun_ajaran' => 'Ganjil 2060/2061',
            'kode_tahun_ajaran' => '20601',
            'tanggal_mulai' => '2060-07-01',
            'tanggal_selesai' => '2060-12-31',
            'is_active' => '1',
            'is_current' => '0',
        ];

        $this->tahunAjaran = TahunAjaran::create($this->payload);
    }

    public function test_must_authenticated_to_access_page(): void
    {
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

    public function test_can_create_tahun_ajaran_with_valid_payload(): void
    {
        $this->testCreate([
            'tahun_ajaran' => 'Genap 2060/2061',
            'kode_tahun_ajaran' => '20602',
            'tanggal_mulai' => '2061-01-01',
            'tanggal_selesai' => '2061-06-30',
            'is_active' => '1',
            'is_current' => '0',
        ]);
    }

    public function test_insert_validation_tahun_ajaran_is_required(): void
    {
        $this->testCreate([
            'tahun_ajaran' => null,
        ], 422);
    }

    public function test_insert_validation_tahun_ajaran_is_min_3_characters(): void
    {
        $this->testCreate([
            'tahun_ajaran' => 'Ge',
        ], 422);
    }

    public function test_insert_validation_tahun_ajaran_is_max_200_characters(): void
    {
        $this->testCreate([
            'tahun_ajaran' => str_repeat('a', 201),
        ], 422);
    }

    public function test_insert_validation_kode_tahun_ajaran_is_required(): void
    {
        $this->testCreate([
            'kode_tahun_ajaran' => null,
        ], 422);
    }

    public function test_insert_validation_kode_tahun_ajaran_is_5_digits(): void
    {
        $this->testCreate([
            'kode_tahun_ajaran' => '1234',
        ], 422);
    }

    public function test_insert_validation_kode_tahun_ajaran_is_unique(): void
    {
        $this->testCreate([
            'kode_tahun_ajaran' => '20601',
        ], 422);
    }

    public function test_insert_validation_tanggal_mulai_is_date(): void
    {
        $this->testCreate([
            'tanggal_mulai' => 'invalid-date',
        ], 422);
    }

    public function test_insert_validation_tanggal_selesai_is_date(): void
    {
        $this->testCreate([
            'tanggal_selesai' => 'invalid-date',
        ], 422);
    }

    public function test_insert_validation_tanggal_selesai_is_after_tanggal_mulai(): void
    {
        $this->testCreate([
            'tanggal_mulai' => '2060-07-01',
            'tanggal_selesai' => '2060-06-30',
        ], 422);
    }

    public function test_can_update_tahun_ajaran_with_valid_payload(): void
    {
        $this->testUpdate(routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_tahun_ajaran_is_required(): void
    {
        $this->testUpdate([
            'tahun_ajaran' => null,
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_tahun_ajaran_is_min_3_characters(): void
    {
        $this->testUpdate([
            'tahun_ajaran' => 'Ge',
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_tahun_ajaran_is_max_200_characters(): void
    {
        $this->testUpdate([
            'tahun_ajaran' => str_repeat('a', 201),
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_kode_tahun_ajaran_is_required(): void
    {
        $this->testUpdate([
            'kode_tahun_ajaran' => null,
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_kode_tahun_ajaran_is_5_digits(): void
    {
        $this->testUpdate([
            'kode_tahun_ajaran' => '1234',
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_kode_tahun_ajaran_is_unique(): void
    {
        $this->testCreate([
            'tahun_ajaran' => 'Genap 2060/2061',
            'kode_tahun_ajaran' => '20602',
            'tanggal_mulai' => '2061-01-01',
            'tanggal_selesai' => '2061-06-30',
            'is_active' => '1',
            'is_current' => '0',
        ]);

        $this->testUpdate([
            'kode_tahun_ajaran' => '20602',
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_tanggal_mulai_is_date(): void
    {
        $this->testUpdate([
            'tanggal_mulai' => 'invalid-date',
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_tanggal_selesai_is_date(): void
    {
        $this->testUpdate([
            'tanggal_selesai' => 'invalid-date',
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_update_validation_tanggal_selesai_is_after_tanggal_mulai(): void
    {
        $this->testUpdate([
            'tanggal_mulai' => '2060-07-01',
            'tanggal_selesai' => '2060-06-30',
        ], 422, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_can_delete_tahun_ajaran(): void
    {
        $this->testDelete(force: true, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);
    }

    public function test_can_restore_tahun_ajaran(): void
    {
        $this->testDelete(true, routeParams: [
            'tahun_ajaran' => $this->tahunAjaran,
        ]);

        $this->testRestore(routeParams: [
            'tahunAjaran' => $this->tahunAjaran,
        ]);
    }
}
