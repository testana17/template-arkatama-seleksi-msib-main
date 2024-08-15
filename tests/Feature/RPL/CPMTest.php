<?php

namespace Tests\Feature\RPL;

use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\CPM;
use App\Models\Rpl\Matakuliah;
use App\Models\User;
use Tests\SimpleTest;

class CPMTest extends SimpleTest
{
    protected $table = 'matakuliah_cpm';

    protected $model = CPM::class;

    protected $route = 'rpl.cpm.';

    protected $routeParam = 'cpm';

    private $matakuliah = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->matakuliah = Matakuliah::create([
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
            'kode_mk' => 'PM001',
            'nama_mk' => 'Pemrograman Mobile',
            'sks_tatap_muka' => 2,
            'sks_praktek' => 2,
            'sks_praktek_lapangan' => 0,
            'sks_simulasi' => 0,
            'sks_praktikum' => 0,
        ]);
        $this->payload = [
            'matkul_id' => $this->matakuliah->id,
            'cpm' => 'Mampu membangun dan mendeploy aplikasi mobile ke platform yang dituju dengan baik dan benar',
            'keterangan' => 'Pemrograman Mobile',
            'is_active' => '1',
        ];
        $this->actingAs($this->user);
        CPM::create($this->payload);
    }

    public function test_must_authenticated_to_access_page(): void
    {
        auth()->logout();
        $response = $this->get(route($this->route.'index', [
            'matakuliah' => $this->matakuliah->id,
        ]));
        $response->assertStatus(302);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route($this->route.'index', [
            'matakuliah' => $this->matakuliah->id,
        ]));
        $response->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'index', [
                'matakuliah' => $this->matakuliah->id,
            ]), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_datatable_entry_histories_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route.'histori', [
                'matakuliah' => $this->matakuliah->id,
            ]), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ]);
        $response->assertJsonStructure(['data']);
    }

    public function test_can_create_cpm_matakuliah_with_valid_payload(): void
    {
        $this->testCreate(status: 201, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ]);
    }

    public function test_insert_validation_cpm_required()
    {
        $this->testCreate(changePayload: [
            'cpm' => null,
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_insert_validation_cpm_min_3()
    {
        $this->testCreate(changePayload: [
            'cpm' => 'ab',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_insert_validation_cpm_max_255()
    {
        $this->testCreate(changePayload: [
            'cpm' => str_repeat('a', 256),
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_insert_validation_cpm_cannot_contains_only_numbers()
    {
        $this->testCreate(changePayload: [
            'cpm' => '1234',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_insert_validation_cpm_cannot_contains_only_symbols()
    {
        $this->testCreate(changePayload: [
            'cpm' => '!!!',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_insert_validation_cpm_cannot_contains_only_spaces()
    {
        $this->testCreate(changePayload: [
            'cpm' => '   ',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_insert_validation_cpm_can_contains_alphanumeric_and_symbols()
    {
        $this->testCreate(changePayload: [
            'cpm' => 'CPM 1 Abc !',
        ], status: 201, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ]);
    }

    public function test_insert_validation_keterangan_required()
    {
        $this->testCreate(changePayload: [
            'keterangan' => null,
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_cannot_contains_only_numbers()
    {
        $this->testCreate(changePayload: [
            'keterangan' => '1234',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_cannot_contains_only_symbols()
    {
        $this->testCreate(changePayload: [
            'keterangan' => '!!!',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_cannot_contains_only_spaces()
    {
        $this->testCreate(changePayload: [
            'keterangan' => '   ',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_insert_validation_keterangan_can_contains_alphanumeric_and_symbols()
    {
        $this->testCreate(changePayload: [
            'keterangan' => 'CPM 1 Abc !',
        ], status: 201, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ]);
    }

    public function test_insert_validation_is_active_required()
    {
        $this->testCreate(changePayload: [
            'is_active' => null,
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('is_active');
    }

    public function test_insert_validation_is_active_only_1_or_0()
    {
        $this->testCreate(changePayload: [
            'is_active' => '2',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
        ])->assertJsonValidationErrors('is_active');
    }

    public function test_can_update_cpm_matakuliah_with_valid_payload(): void
    {
        $this->testUpdate(changePayload: [
            'cpm' => 'Mampu membangun aplikasi Android',
        ], routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ]);
    }

    public function test_update_validation_cpm_required()
    {
        $this->testUpdate(changePayload: [
            'cpm' => null,
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_update_validation_cpm_min_3()
    {
        $this->testUpdate(changePayload: [
            'cpm' => 'ab',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_update_validation_cpm_max_255()
    {
        $this->testUpdate(changePayload: [
            'cpm' => str_repeat('a', 256),
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_update_validation_cpm_cannot_contains_only_numbers()
    {
        $this->testUpdate(changePayload: [
            'cpm' => '1234',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_update_validation_cpm_cannot_contains_only_symbols()
    {
        $this->testUpdate(changePayload: [
            'cpm' => '!!!',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_update_validation_cpm_cannot_contains_only_spaces()
    {
        $this->testUpdate(changePayload: [
            'cpm' => '   ',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('cpm');
    }

    public function test_update_validation_cpm_can_contains_alphanumeric_and_symbols()
    {
        $this->testUpdate(changePayload: [
            'cpm' => 'CPM 1 Abc !',
        ], routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ]);
    }

    public function test_update_validation_keterangan_required()
    {
        $this->testUpdate(changePayload: [
            'keterangan' => null,
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_cannot_contains_only_numbers()
    {
        $this->testUpdate(changePayload: [
            'keterangan' => '1234',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_cannot_contains_only_symbols()
    {
        $this->testUpdate(changePayload: [
            'keterangan' => '!!!',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_cannot_contains_only_spaces()
    {
        $this->testUpdate(changePayload: [
            'keterangan' => '   ',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('keterangan');
    }

    public function test_update_validation_keterangan_can_contains_alphanumeric_and_symbols()
    {
        $this->testUpdate(changePayload: [
            'keterangan' => 'CPM 1 Abc !',
        ], routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ]);
    }

    public function test_update_validation_is_active_required()
    {
        $this->testUpdate(changePayload: [
            'is_active' => null,
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('is_active');
    }

    public function test_update_validation_is_active_only_1_or_0()
    {
        $this->testUpdate(changePayload: [
            'is_active' => '2',
        ], status: 422, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ])->assertJsonValidationErrors('is_active');
    }

    public function test_can_delete_cpm_matakuliah(): void
    {
        $this->testDelete(force: true, routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ]);
    }

    public function test_can_restore_cpm_matakuliah(): void
    {
        $this->testRestore(routeParams: [
            'matakuliah' => $this->matakuliah->id,
            'cpm' => $this->matakuliah->cpm()->first()->id,
        ]);
    }
}
