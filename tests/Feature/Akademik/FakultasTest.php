<?php

namespace Tests\Feature\Akademik;

use App\Models\Akademik\Fakultas;
use App\Models\Akademik\PerguruanTinggi;
use App\Models\User;
use Tests\SimpleTest;

class FakultasTest extends SimpleTest
{
    protected $table = 'ref_fakultas';

    protected $model = Fakultas::class;

    protected $route = 'akademik.fakultas.';

    protected $routeParam = 'fakultas';

    public function setUp(): void
    {
        parent::setUp();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomCode = '';
        do {
            $randomCode = substr(str_shuffle($characters), 0, 5);
        } while (ctype_alpha($randomCode) || ctype_digit($randomCode));

        $pt = PerguruanTinggi::first();
        $parsedUrl = parse_url($pt->website);
        $domain = $parsedUrl['host'];
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->payload = [
            'nama_fakultas' => 'Fakultas Ekonomi',
            'singkatan' => 'FE',
            'nama_inggris' => 'Faculty of Economy',
            'alamat' => 'Jl. Raya Malang No. 6',
            'kota' => 'Malang',
            'telepon' => '6283899010123',
            'fax' => '623416060',
            'description' => 'Fakultas Ekonomi Universitas',
        ];
        $this->payload['kode'] = $randomCode;
        $this->payload['website'] = 'https://'.strtolower($this->payload['singkatan']).'.'.$domain;
        $this->payload['email'] = strtolower($this->payload['kode']).'@'.$domain;
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

    public function test_can_create_fakultas_with_valid_payload(): void
    {
        $this->testCreate();
    }

    public function test_insert_validation_kode_required(): void
    {
        $this->testCreate([
            'kode' => null,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_unique(): void
    {
        $this->testCreate([
            'kode' => $this->model::first()->kode,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_alpha_num(): void
    {
        $this->testCreate([
            'kode' => 'ABC DEF',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_alpha_num_spaces_with_alphabet(): void
    {
        $this->testCreate([
            'kode' => '123456',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_alpha_num_spaces_with_number(): void
    {
        $this->testCreate([
            'kode' => 'ABCDEF',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_min(): void
    {
        $this->testCreate([
            'kode' => 'AB',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_kode_max(): void
    {
        $this->testCreate([
            'kode' => 'ABCDEF',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_insert_validation_nama_fakultas_required(): void
    {
        $this->testCreate([
            'nama_fakultas' => null,
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_insert_validation_nama_fakultas_min(): void
    {
        $this->testCreate([
            'nama_fakultas' => 'AB',
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_insert_validation_nama_fakultas_max(): void
    {
        $this->testCreate([
            'nama_fakultas' => str_repeat('A', 101),
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_insert_validation_nama_fakultas_alpha_spaces(): void
    {
        $this->testCreate([
            'nama_fakultas' => 'AB123',
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_insert_validation_singkatan_required(): void
    {
        $this->testCreate([
            'singkatan' => null,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_min(): void
    {
        $this->testCreate([
            'singkatan' => 'A',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_max(): void
    {
        $this->testCreate([
            'singkatan' => str_repeat('A', 11),
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_alpha_spaces(): void
    {
        $this->testCreate([
            'singkatan' => 'AB123',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_singkatan_unique(): void
    {
        $this->testCreate([
            'singkatan' => $this->model::first()->singkatan,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_insert_validation_nama_inggris_min(): void
    {
        $this->testCreate([
            'nama_inggris' => 'AB',
        ], 422)->assertJsonValidationErrors('nama_inggris');
    }

    public function test_insert_validation_nama_inggris_max(): void
    {
        $this->testCreate([
            'nama_inggris' => str_repeat('A', 101),
        ], 422)->assertJsonValidationErrors('nama_inggris');
    }

    public function test_insert_validation_nama_inggris_alpha_spaces(): void
    {
        $this->testCreate([
            'nama_inggris' => 'AB123',
        ], 422)->assertJsonValidationErrors('nama_inggris');
    }

    public function test_insert_validation_alamat_min(): void
    {
        $this->testCreate([
            'alamat' => 'AB',
        ], 422)->assertJsonValidationErrors('alamat');
    }

    public function test_insert_validation_alamat_max(): void
    {
        $this->testCreate([
            'alamat' => str_repeat('A', 256),
        ], 422)->assertJsonValidationErrors('alamat');
    }

    public function test_insert_validation_kota_min(): void
    {
        $this->testCreate([
            'kota' => 'AB',
        ], 422)->assertJsonValidationErrors('kota');
    }

    public function test_insert_validation_kota_max(): void
    {
        $this->testCreate([
            'kota' => str_repeat('A', 101),
        ], 422)->assertJsonValidationErrors('kota');
    }

    public function test_insert_validation_kota_alpha_spaces(): void
    {
        $this->testCreate([
            'kota' => 'AB123',
        ], 422)->assertJsonValidationErrors('kota');
    }

    public function test_insert_validation_telepon_digits_between(): void
    {
        $this->testCreate([
            'telepon' => '123456',
        ], 422)->assertJsonValidationErrors('telepon');
    }

    public function test_insert_validation_telepon_starts_with(): void
    {
        $this->testCreate([
            'telepon' => '123456',
        ], 422)->assertJsonValidationErrors('telepon');
    }

    public function test_insert_validation_fax_digits_between(): void
    {
        $this->testCreate([
            'fax' => '123456',
        ], 422)->assertJsonValidationErrors('fax');
    }

    public function test_insert_validation_fax_starts_with(): void
    {
        $this->testCreate([
            'fax' => '123456',
        ], 422)->assertJsonValidationErrors('fax');
    }

    public function test_insert_validation_email_email(): void
    {
        $this->testCreate([
            'email' => 'email',
        ], 422)->assertJsonValidationErrors('email');
    }

    public function test_insert_validation_email_unique(): void
    {
        $this->testCreate([
            'email' => $this->model::first()->email,
        ], 422)->assertJsonValidationErrors('email');
    }

    public function test_insert_validation_website_url(): void
    {
        $this->testCreate([
            'website' => 'website',
        ], 422)->assertJsonValidationErrors('website');
    }

    public function test_insert_validation_description_min(): void
    {
        $this->testCreate([
            'description' => 'AB',
        ], 422)->assertJsonValidationErrors('description');
    }

    public function test_insert_validation_description_max(): void
    {
        $this->testCreate([
            'description' => str_repeat('A', 256),
        ], 422)->assertJsonValidationErrors('description');
    }

    public function test_can_update_fakultas(): void
    {
        $this->testUpdate();
    }

    public function test_update_validation_kode_required(): void
    {
        $this->testUpdate([
            'kode' => null,
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_kode_alpha_num(): void
    {
        $this->testUpdate([
            'kode' => 'ABC DEF',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_kode_alpha_num_spaces_with_alphabet(): void
    {
        $this->testUpdate([
            'kode' => '123456',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_kode_alpha_num_spaces_with_number(): void
    {
        $this->testUpdate([
            'kode' => 'ABCDEF',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_kode_min(): void
    {
        $this->testUpdate([
            'kode' => 'AB',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_kode_max(): void
    {
        $this->testUpdate([
            'kode' => 'ABCDEF',
        ], 422)->assertJsonValidationErrors('kode');
    }

    public function test_update_validation_nama_fakultas_required(): void
    {
        $this->testUpdate([
            'nama_fakultas' => null,
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_update_validation_nama_fakultas_min(): void
    {
        $this->testUpdate([
            'nama_fakultas' => 'AB',
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_update_validation_nama_fakultas_max(): void
    {
        $this->testUpdate([
            'nama_fakultas' => str_repeat('A', 101),
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_update_validation_nama_fakultas_alpha_spaces(): void
    {
        $this->testUpdate([
            'nama_fakultas' => 'AB123',
        ], 422)->assertJsonValidationErrors('nama_fakultas');
    }

    public function test_update_validation_singkatan_required(): void
    {
        $this->testUpdate([
            'singkatan' => null,
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_singkatan_min(): void
    {
        $this->testUpdate([
            'singkatan' => 'A',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_singkatan_max(): void
    {
        $this->testUpdate([
            'singkatan' => str_repeat('A', 12),
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_singkatan_alpha_spaces(): void
    {
        $this->testUpdate([
            'singkatan' => 'AB123',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_nama_inggris_min(): void
    {
        $this->testUpdate([
            'nama_inggris' => 'AB',
        ], 422)->assertJsonValidationErrors('nama_inggris');
    }

    public function test_update_validation_nama_inggris_max(): void
    {
        $this->testUpdate([
            'nama_inggris' => str_repeat('A', 101),
        ], 422)->assertJsonValidationErrors('nama_inggris');
    }

    public function test_update_validation_nama_inggris_alpha_spaces(): void
    {
        $this->testUpdate([
            'nama_inggris' => 'AB123',
        ], 422)->assertJsonValidationErrors('nama_inggris');
    }

    public function test_update_validation_alamat_min(): void
    {
        $this->testUpdate([
            'alamat' => 'AB',
        ], 422)->assertJsonValidationErrors('alamat');
    }

    public function test_update_validation_alamat_max(): void
    {
        $this->testUpdate([
            'alamat' => str_repeat('A', 256),
        ], 422)->assertJsonValidationErrors('alamat');
    }

    public function test_update_validation_kota_min(): void
    {
        $this->testUpdate([
            'kota' => 'AB',
        ], 422)->assertJsonValidationErrors('kota');
    }

    public function test_update_validation_kota_max(): void
    {
        $this->testUpdate([
            'kota' => str_repeat('A', 101),
        ], 422)->assertJsonValidationErrors('kota');
    }

    public function test_update_validation_kota_alpha_spaces(): void
    {
        $this->testUpdate([
            'kota' => 'AB123',
        ], 422)->assertJsonValidationErrors('kota');
    }

    public function test_update_validation_telepon_digits_between(): void
    {
        $this->testUpdate([
            'telepon' => '123456',
        ], 422)->assertJsonValidationErrors('telepon');
    }

    public function test_update_validation_telepon_starts_with(): void
    {
        $this->testUpdate([
            'telepon' => '123456',
        ], 422)->assertJsonValidationErrors('telepon');
    }

    public function test_update_validation_fax_digits_between(): void
    {
        $this->testUpdate([
            'fax' => '123456',
        ], 422)->assertJsonValidationErrors('fax');
    }

    public function test_update_validation_fax_starts_with(): void
    {
        $this->testUpdate([
            'fax' => '123456',
        ], 422)->assertJsonValidationErrors('fax');
    }

    public function test_update_validation_email_email(): void
    {
        $this->testUpdate([
            'email' => 'email',
        ], 422)->assertJsonValidationErrors('email');
    }

    public function test_update_validation_website_url(): void
    {
        $this->testUpdate([
            'website' => 'website',
        ], 422)->assertJsonValidationErrors('website');
    }

    public function test_update_validation_description_min(): void
    {
        $this->testUpdate([
            'description' => 'AB',
        ], 422)->assertJsonValidationErrors('description');
    }

    public function test_update_validation_description_max(): void
    {
        $this->testUpdate([
            'description' => str_repeat('A', 256),
        ], 422)->assertJsonValidationErrors('description');
    }

    public function test_can_delete_fakultas(): void
    {
        $this->testDelete();
    }

    public function test_can_restore_fakultas(): void
    {
        $this->testRestore();
    }

    public function test_update_validation_singkatan_unique(): void
    {
        $this->testUpdate([
            'singkatan' => 'FT',
        ], 422)->assertJsonValidationErrors('singkatan');
    }

    public function test_update_validation_email_unique(): void
    {
        $this->testUpdate([
            'email' => 'tek01@um.ac.id',
        ], 422)->assertJsonValidationErrors('email');
    }

    public function test_update_validation_kode_unique(): void
    {
        $this->testUpdate([
            'kode' => 'TEK01',
        ], 422)->assertJsonValidationErrors('kode');
    }
}
