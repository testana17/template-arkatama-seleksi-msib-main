<?php

namespace Tests\Feature\Akademik;

use App\Models\Master\KategoriBerita;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\CRUDTestCase;

class KategoriBeritaTest extends CRUDTestCase
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
            'name' => 'Kehidupan',
            'description' => 'Kategori Berita Kehidupan',
        ];
        $this->route = 'master.kategori-berita.';
        $this->table = 'ref_kategori_news';

        $this->setBaseUser($adminUser);
        $this->setBaseRoute('master.kategori-berita');
        $this->setBaseModel(KategoriBerita::class);
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

    public function test_can_create_kategori_berita(): Model
    {
        [$response, $model] = $this->testCreate(attributes: $this->defaultPayload);

        return $model;
    }

    public function test_can_update_kategori_berita()
    {
        $model = $this->test_can_create_kategori_berita();
        $updatePayload = array_merge($this->defaultPayload, [
            'name' => 'Kehidupan Pribadi',
            'description' => 'Kategori Berita Kehidupan dari seorang insan yang berjuang dalam kehidupa',
        ]);
        $response = $this->testUpdate(model: $model, attributes: $updatePayload);
    }

    public function test_can_delete_kategori_berita()
    {
        $model = $this->test_can_create_kategori_berita();
        $response = $this->testDelete(model: $model, isSoftDeleting: true);

        return $model;
    }

    public function test_can_restore_kategori_berita()
    {
        $model = $this->test_can_delete_kategori_berita();
        $response = $this->testRestore(model: $model);
    }

    public function test_validation_create_kategori_berita_with_name_required()
    {
        $this->defaultPayload['name'] = '';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_kategori_berita_with_name_regex()
    {
        $this->defaultPayload['name'] = 'Kehidupan@';
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_kategori_berita_with_name_unique()
    {
        $this->test_can_create_kategori_berita();
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_create_kategori_berita_with_description_string()
    {
        $this->defaultPayload['description'] = 123;
        $this->testCreate($this->defaultPayload, status: 422);
    }

    public function test_validation_update_kategori_berita_with_name_required()
    {
        $model = $this->test_can_create_kategori_berita();
        $this->defaultPayload['name'] = '';
        $this->testUpdate(model: $model, attributes: $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kategori_berita_with_name_regex()
    {
        $model = $this->test_can_create_kategori_berita();
        $this->defaultPayload['name'] = 'Kehidupan@';
        $this->testUpdate(model: $model, attributes: $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kategori_berita_with_name_unique()
    {
        $model = $this->test_can_create_kategori_berita();
        $this->defaultPayload['name'] = 'Umum';
        $this->testUpdate(model: $model, attributes: $this->defaultPayload, status: 422);
    }

    public function test_validation_update_kategori_berita_with_name_unique_except_self()
    {
        $model = $this->test_can_create_kategori_berita();
        $this->testUpdate(model: $model, attributes: $this->defaultPayload, status: 200);
    }

    public function test_validation_update_kategori_berita_with_description_string()
    {
        $model = $this->test_can_create_kategori_berita();
        $this->defaultPayload['description'] = 123;
        $this->testUpdate(model: $model, attributes: $this->defaultPayload, status: 422);
    }
}
