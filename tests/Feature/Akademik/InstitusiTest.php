<?php

namespace Tests\Feature\Akademik;

use App\Models\Akademik\PerguruanTinggi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\CreatesApplication;
use Tests\SimpleTest;

class InstitusiTest extends SimpleTest
{
    use CreatesApplication, DatabaseTransactions;

    protected $table = 'perguruan_tinggi';

    protected $model = PerguruanTinggi::class;

    protected $route = 'akademik.data-institusi.';

    protected $routeParam = 'institusi';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->payload = [
            'nama_perguruan_tinggi' => 'Universitas Test',
            'kode_perguruan_tinggi' => '001031',
            'telepon' => '6283899010123',
            'email' => 'urektorat@ut.ac.id',
            'website' => 'https://ut.ac.id',
            'jalan' => 'Jl. Raya Malang No. 6',
            'dusun' => 'Raya',
            'rt_rw' => '17/07',
            'kelurahan' => 'Raya',
            'kode_pos' => '10101',
            'bank' => 'Bank BRI',
            'unit_cabang' => 'Kantor Cabang Malang',
            'nomor_rekening' => '002101000000001',
            'sk_pendirian' => 'Keppres 93 Tahun 1999',
            'tanggal_sk_pendirian' => '1999-04-08',
            'sk_izin_operasional' => 'Keppres 93 Tahun 1999',
            'tanggal_sk_izin_operasional' => '1999-04-08',
            'predikat_akreditasi' => 'Unggul',
            'sk_akreditasi' => '810/SK/BAN-PT/AK-SK/PT/IX/2021',
            'tanggal_sk_akreditasi' => '2021-07-09',
            'logo' => UploadedFile::fake()->create('file.png', 1000, 'image/png'),
        ];
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

    public function test_can_create_institusi()
    {
        $this->testCreate(assertDbHasExcept: ['logo']);
    }

    public function test_can_update_institusi()
    {
        $this->testCreate(changePayload: [
            'nama_perguruan_tinggi' => 'Universitas Test Nama Updated',
            'kode_perguruan_tinggi' => '001031',
            'telepon' => '6283899010123',
        ], assertDbHasExcept: ['logo']);
    }

    public function test_update_validation_nama_perguruan_tinggi_required(): void
    {
        $this->testCreate([
            'nama_perguruan_tinggi' => null,
        ], 422)->assertJsonValidationErrors('nama_perguruan_tinggi');
    }

    public function test_update_validation_nama_perguruan_tinggi_min_3(): void
    {
        $this->testCreate([
            'nama_perguruan_tinggi' => 'aa',
        ], 422)->assertJsonValidationErrors('nama_perguruan_tinggi');
    }

    public function test_update_validation_nama_perguruan_tinggi_max_200(): void
    {
        $this->testCreate([
            'nama_perguruan_tinggi' => str_repeat('a', 201),
        ], 422)->assertJsonValidationErrors('nama_perguruan_tinggi');
    }

    public function test_update_validation_kode_perguruan_tinggi_required(): void
    {
        $this->testCreate([
            'kode_perguruan_tinggi' => null,
        ], 422)->assertJsonValidationErrors('kode_perguruan_tinggi');
    }

    public function test_update_validation_kode_perguruan_tinggi_min_3(): void
    {
        $this->testCreate([
            'kode_perguruan_tinggi' => 'aa',
        ], 422)->assertJsonValidationErrors('kode_perguruan_tinggi');
    }

    public function test_update_validation_kode_perguruan_tinggi_max_100(): void
    {
        $this->testCreate([
            'kode_perguruan_tinggi' => str_repeat('a', 101),
        ], 422)->assertJsonValidationErrors('kode_perguruan_tinggi');
    }

    public function test_update_validation_telepon_required(): void
    {
        $this->testCreate([
            'telepon' => null,
        ], 422)->assertJsonValidationErrors('telepon');
    }

    public function test_update_validation_telepon_digits_between_7_20(): void
    {
        $this->testCreate([
            'telepon' => '123456',
        ], 422)->assertJsonValidationErrors('telepon');
    }

    public function test_update_validation_telepon_starts_with_0_62(): void
    {
        $this->testCreate([
            'telepon' => '123456',
        ], 422)->assertJsonValidationErrors('telepon');
    }

    public function test_update_validation_email_required(): void
    {
        $this->testCreate([
            'email' => null,
        ], 422)->assertJsonValidationErrors('email');
    }

    public function test_update_validation_email_must_valid_email(): void
    {
        $this->testCreate([
            'email' => 'email',
        ], 422)->assertJsonValidationErrors('email');
    }

    public function test_update_validation_email_max_100(): void
    {
        $this->testCreate([
            'email' => str_repeat('a', 101).'@arkatama.test',
        ], 422)->assertJsonValidationErrors('email');
    }

    public function test_update_validation_website_required(): void
    {
        $this->testCreate([
            'website' => null,
        ], 422)->assertJsonValidationErrors('website');
    }

    public function test_update_validation_website_must_valid_url(): void
    {
        $this->testCreate([
            'website' => 'website',
        ], 422)->assertJsonValidationErrors('website');
    }

    public function test_update_validation_website_max_100(): void
    {
        $this->testCreate([
            'website' => str_repeat('a', 101),
        ], 422)->assertJsonValidationErrors('website');
    }

    public function test_update_validation_jalan_required(): void
    {
        $this->testCreate([
            'jalan' => null,
        ], 422)->assertJsonValidationErrors('jalan');
    }

    public function test_update_validation_jalan_min_3(): void
    {
        $this->testCreate([
            'jalan' => 'aa',
        ], 422)->assertJsonValidationErrors('jalan');
    }

    public function test_update_validation_jalan_max_200(): void
    {
        $this->testCreate([
            'jalan' => str_repeat('a', 201),
        ], 422)->assertJsonValidationErrors('jalan');
    }

    public function test_update_validation_dusun_required(): void
    {
        $this->testCreate([
            'dusun' => null,
        ], 422)->assertJsonValidationErrors('dusun');
    }

    public function test_update_validation_dusun_min_3(): void
    {
        $this->testCreate([
            'dusun' => 'aa',
        ], 422)->assertJsonValidationErrors('dusun');
    }

    public function test_update_validation_dusun_max_100(): void
    {
        $this->testCreate([
            'dusun' => str_repeat('a', 101),
        ], 422)->assertJsonValidationErrors('dusun');
    }

    public function test_update_validation_rt_rw_required(): void
    {
        $this->testCreate([
            'rt_rw' => null,
        ], 422)->assertJsonValidationErrors('rt_rw');
    }

    public function test_update_validation_rt_rw_min_3(): void
    {
        $this->testCreate([
            'rt_rw' => 'aa',
        ], 422)->assertJsonValidationErrors('rt_rw');
    }

    public function test_update_validation_rt_rw_max_100(): void
    {
        $this->testCreate([
            'rt_rw' => str_repeat('a', 101),
        ], 422)->assertJsonValidationErrors('rt_rw');
    }

    public function test_update_validation_kelurahan_required(): void
    {
        $this->testCreate([
            'kelurahan' => null,
        ], 422)->assertJsonValidationErrors('kelurahan');
    }

    public function test_update_validation_kelurahan_min_3(): void
    {
        $this->testCreate([
            'kelurahan' => 'aa',
        ], 422)->assertJsonValidationErrors('kelurahan');
    }

    public function test_update_validation_kelurahan_max_100(): void
    {
        $this->testCreate([
            'kelurahan' => str_repeat('a', 101),
        ], 422)->assertJsonValidationErrors('kelurahan');
    }

    public function test_update_validation_kode_pos_required(): void
    {
        $this->testCreate([
            'kode_pos' => null,
        ], 422)->assertJsonValidationErrors('kode_pos');
    }

    public function test_update_validation_kode_pos_min_3(): void
    {
        $this->testCreate([
            'kode_pos' => 'aa',
        ], 422)->assertJsonValidationErrors('kode_pos');
    }

    public function test_update_validation_kode_pos_max_10_char(): void
    {
        $this->testCreate([
            'kode_pos' => str_repeat('a', 11),
        ], 422)->assertJsonValidationErrors('kode_pos');
    }

    public function test_update_validation_bank_required(): void
    {
        $this->testCreate([
            'bank' => null,
        ], 422)->assertJsonValidationErrors('bank');
    }

    public function test_update_validation_bank_min_3_char(): void
    {
        $this->testCreate([
            'bank' => 'aa',
        ], 422)->assertJsonValidationErrors('bank');
    }

    public function test_update_validation_bank_max_255_char(): void
    {
        $this->testCreate([
            'bank' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('bank');
    }

    public function test_update_validation_unit_cabang_required(): void
    {
        $this->testCreate([
            'unit_cabang' => null,
        ], 422)->assertJsonValidationErrors('unit_cabang');
    }

    public function test_update_validation_unit_cabang_min_3_char(): void
    {
        $this->testCreate([
            'unit_cabang' => 'aa',
        ], 422)->assertJsonValidationErrors('unit_cabang');
    }

    public function test_update_validation_unit_cabang_max_255_char(): void
    {
        $this->testCreate([
            'unit_cabang' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('unit_cabang');
    }

    public function test_update_validation_nomor_rekening_required(): void
    {
        $this->testCreate([
            'nomor_rekening' => null,
        ], 422)->assertJsonValidationErrors('nomor_rekening');
    }

    public function test_update_validation_nomor_rekening_min_3_char(): void
    {
        $this->testCreate([
            'nomor_rekening' => 'aa',
        ], 422)->assertJsonValidationErrors('nomor_rekening');
    }

    public function test_update_validation_nomor_rekening_max_30_char(): void
    {
        $this->testCreate([
            'nomor_rekening' => str_repeat('a', 31),
        ], 422)->assertJsonValidationErrors('nomor_rekening');
    }

    public function test_update_validation_sk_pendirian_required(): void
    {
        $this->testCreate([
            'sk_pendirian' => null,
        ], 422)->assertJsonValidationErrors('sk_pendirian');
    }

    public function test_update_validation_sk_pendirian_min_3_char(): void
    {
        $this->testCreate([
            'sk_pendirian' => 'aa',
        ], 422)->assertJsonValidationErrors('sk_pendirian');
    }

    public function test_update_validation_sk_pendirian_max_255_char(): void
    {
        $this->testCreate([
            'sk_pendirian' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('sk_pendirian');
    }

    public function test_update_validation_tanggal_sk_pendirian_required(): void
    {
        $this->testCreate([
            'tanggal_sk_pendirian' => null,
        ], 422)->assertJsonValidationErrors('tanggal_sk_pendirian');
    }

    public function test_update_validation_tanggal_sk_pendirian_date(): void
    {
        $this->testCreate([
            'tanggal_sk_pendirian' => 'aa',
        ], 422)->assertJsonValidationErrors('tanggal_sk_pendirian');
    }

    public function test_update_validation_sk_izin_operasional_required(): void
    {
        $this->testCreate([
            'sk_izin_operasional' => null,
        ], 422)->assertJsonValidationErrors('sk_izin_operasional');
    }

    public function test_update_validation_sk_izin_operasional_min_3_char(): void
    {
        $this->testCreate([
            'sk_izin_operasional' => 'aa',
        ], 422)->assertJsonValidationErrors('sk_izin_operasional');
    }

    public function test_update_validation_sk_izin_operasional_max_255_char(): void
    {
        $this->testCreate([
            'sk_izin_operasional' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('sk_izin_operasional');
    }

    public function test_update_validation_tanggal_sk_izin_operasional_required(): void
    {
        $this->testCreate([
            'tanggal_sk_izin_operasional' => null,
        ], 422)->assertJsonValidationErrors('tanggal_sk_izin_operasional');
    }

    public function test_update_validation_tanggal_sk_izin_operasional_date(): void
    {
        $this->testCreate([
            'tanggal_sk_izin_operasional' => 'aa',
        ], 422)->assertJsonValidationErrors('tanggal_sk_izin_operasional');
    }

    public function test_update_validation_predikat_akreditasi_required(): void
    {
        $this->testCreate([
            'predikat_akreditasi' => null,
        ], 422)->assertJsonValidationErrors('predikat_akreditasi');
    }

    public function test_update_validation_predikat_akreditasi_min_1_char(): void
    {
        $this->testCreate([
            'predikat_akreditasi' => '',
        ], 422)->assertJsonValidationErrors('predikat_akreditasi');
    }

    public function test_update_validation_predikat_akreditasi_max_255_char(): void
    {
        $this->testCreate([
            'predikat_akreditasi' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('predikat_akreditasi');
    }

    public function test_update_validation_sk_akreditasi_required(): void
    {
        $this->testCreate([
            'sk_akreditasi' => null,
        ], 422)->assertJsonValidationErrors('sk_akreditasi');
    }

    public function test_update_validation_sk_akreditasi_min_3_char(): void
    {
        $this->testCreate([
            'sk_akreditasi' => 'aa',
        ], 422)->assertJsonValidationErrors('sk_akreditasi');
    }

    public function test_update_validation_sk_akreditasi_max_255_char(): void
    {
        $this->testCreate([
            'sk_akreditasi' => str_repeat('a', 256),
        ], 422)->assertJsonValidationErrors('sk_akreditasi');
    }

    public function test_update_validation_tanggal_sk_akreditasi_required(): void
    {
        $this->testCreate([
            'tanggal_sk_akreditasi' => null,
        ], 422)->assertJsonValidationErrors('tanggal_sk_akreditasi');
    }

    public function test_update_validation_tanggal_sk_akreditasi_date(): void
    {
        $this->testCreate([
            'tanggal_sk_akreditasi' => 'aa',
        ], 422)->assertJsonValidationErrors('tanggal_sk_akreditasi');
    }
}
