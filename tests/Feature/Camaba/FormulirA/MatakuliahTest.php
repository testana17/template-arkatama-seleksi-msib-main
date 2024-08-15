<?php

namespace Tests\Feature\Camaba\FormulirA;

use App\Models\Payment\Pembayaran;
use App\Models\Rpl\CPM;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\MatakuliahSetting;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Database\Seeders\MatakuliahSettingSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\CRUDTestCase;

class MatakuliahTest extends CRUDTestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    protected $base_route;

    protected $base_user;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->base_route = 'formulir-f02.matakuliah';

        $pembayaran = Pembayaran::factory()->create();
        $this->base_user = $pembayaran->register->user;
        $this->admin = User::where('email', 'admin@arkatama.test')->first();

    }

    public function test_cant_acces_when_not_in_registration_schedule(): void
    {
        $this->makeRegistrationSchedulePassed();
        $this->actingAs($this->base_user)->get(route('formulir-f02.data-camaba.index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_cant_access_when_registration_file_not_fullfilled(): void
    {
        $this->removeAllUploadedRegistrationFile();
        $this->actingAs($this->base_user)->get(route('formulir-f02.data-camaba.index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_can_access_when_in_registration_schedule_and_the_registration_file_fullfilled(): void
    {
        $this->makeRegistrationSchedulePassed();
        $this->giveAccess();
        $this->actingAs($this->base_user)->get(route('formulir-f02.data-camaba.index'))
            ->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $this->removeAllUploadedRegistrationFile();
        $this->giveAccess();
        $this->testShowDatatable();
    }

    public function test_cannot_enroll_matakuliah_when_not_in_registration_schedule(): void
    {
        $this->makeRegistrationSchedulePassed();
        $this->actingAs($this->base_user)->post(route('formulir-f02.matakuliah.store'), [])
            ->assertRedirectToRoute('dashboard');
    }

    public function test_cannot_enroll_matakuliah_when_registration_file_not_fullfilled(): void
    {
        $this->actingAs($this->base_user)->post(route('formulir-f02.matakuliah.store'), [])
            ->assertRedirectToRoute('dashboard');
        $this->actingAs($this->base_user)->post(route('formulir-f02.matakuliah.store'), [])
            ->assertRedirectToRoute('dashboard')->assertSessionHas('error', 'Mohon untuk melengkapi berkas persyaratan terlebih dahulu');
    }

    public function test_cannot_enroll_matakuliah_that_doesnt_has_cpm(): void
    {
        $this->seed(MatakuliahSettingSeeder::class);
        $this->giveAccess();
        $payload = [];
        MatakuliahSetting::with('matakuliah.cpm')->get()->each(function ($item, $i) use (&$payload) {
            CPM::where('matkul_id', $item['matakuliah']['id'])->delete();
            $payload['mk_'.$item['matakuliah']['id']] = '1';
        });

        $this->request(route('formulir-f02.matakuliah.store'), 'POST', $this->base_user, $payload, function ($response) {
            $response->assertStatus(400);
        });
    }

    public function test_can_enroll_matakuliah(): void
    {
        $this->giveAccess();

        $listMK = MatakuliahSetting::with('matakuliah.cpm')->whereRelation('matakuliah', function ($q) {
            $q->where('prodi_id', $this->base_user->register->prodi_id);
        })->get();

        foreach ($listMK->toArray() as $i => $item) {
            $payloadEnroll['mk_'.$item['matakuliah']['id']] = $i == 0 ? '1' : '0';
        }

        $this->request(route('formulir-f02.matakuliah.store'), 'POST', $this->base_user, $payloadEnroll, function ($response) use ($listMK) {
            $response->assertSuccessful();
            $availableCPM_MK = $listMK->reduce(function ($carry, $item, $key) {
                $item->matakuliah->cpm->each(function ($cpm) use (&$carry, $item, $key) {
                    $carry[] = [
                        'formulir_id' => $this->base_user->formulir->id,
                        'matkul_cpm_id' => $cpm->id,
                        'matkul_id' => $item->matakuliah->id,
                        'taken' => $key == 0,
                    ];
                });

                return $carry;
            });

            $takenMK = collect($availableCPM_MK)->filter(function ($item) {
                return $item['taken'] == true;
            })->map(function ($item) {
                unset($item['taken']);

                return $item;
            });

            $takenMK->each(function ($item) {
                $this->assertDatabaseHas('formulir_matakuliah_cpm', $item);
            });
        });
    }

    public function test_can_unenrol_the_enrolled_matakuliah(): void
    {
        $this->test_can_enroll_matakuliah();
        $takenMK = FormulirMatakuliahCPM::where('formulir_id', $this->base_user->formulir->id)->groupBy(['matkul_id'])->get(['matkul_id']);
        $listMkWithCPM = FormulirMatakuliahCPM::where('formulir_id', $this->base_user->formulir->id)->get(['matkul_id', 'matkul_cpm_id', 'formulir_id']);
        $payloadEnroll = $takenMK->reduce(function ($carry, $item, $key) {
            $carry['mk_'.$item['matkul_id']] = '0';

            return $carry;
        }, []);
        $this->request(route('formulir-f02.matakuliah.store'), 'POST', $this->base_user, $payloadEnroll, function ($response) use ($listMkWithCPM) {
            $response->assertSuccessful();
            $listMkWithCPM->each(function ($item) {
                $this->assertSoftDeleted('formulir_matakuliah_cpm', $item->toArray());
            });
        });
    }

    private function makeRegistrationSchedulePassed()
    {
        $registration = $this->base_user->register;
        ProdiPilihan::where([
            'tahun_ajaran_id' => $registration->tahun_ajaran_id,
            'prodi_id' => $registration->prodi_id,
        ])->update([
            'tanggal_mulai_pendaftaran' => now()->addDay(-50),
            'tanggal_selesai_pendaftaran' => now()->addDays(-30),
            'tanggal_mulai_administrasi' => now()->addDay(-30),
            'tanggal_selesai_administrasi' => now()->addDays(-10),
        ]);
    }

    private function removeAllUploadedRegistrationFile()
    {
        FormulirBerkasPersyaratan::where('formulir_id', $this->base_user->formulir->id)->delete();
    }

    private function giveAccess()
    {
        $registration = $this->base_user->register;
        $formulir = $this->base_user->formulir;
        $this->actingAs($this->admin);
        ProdiPilihan::where([
            'tahun_ajaran_id' => $registration->tahun_ajaran_id,
            'prodi_id' => $registration->prodi_id,
        ])->update([
            'tanggal_mulai_pendaftaran' => now()->addDay(-30),
            'tanggal_selesai_pendaftaran' => now()->addDays(-10),
            'tanggal_mulai_administrasi' => now()->addDay(-10),
            'tanggal_selesai_administrasi' => now()->addDays(30),
        ]);

        SyaratPendaftaran::where('prodi_id', $registration->prodi_id)->each(function ($syarat) use ($formulir) {
            FormulirBerkasPersyaratan::create([
                'formulir_id' => $formulir->id,
                'keterangan' => 'ashflsdflaskdm',
                'persyaratan_id' => $syarat->id,
                'file_pendukung' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
            ]);
        });
    }
}
