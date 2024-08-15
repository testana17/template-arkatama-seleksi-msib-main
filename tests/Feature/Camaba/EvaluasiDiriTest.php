<?php

namespace Tests\Feature\Camaba;

use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\MatakuliahSetting;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Database\Seeders\MatakuliahSettingSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class EvaluasiDiriTest extends TestCase
{
    use DatabaseTransactions;

    private $base_user;

    private $base_route;

    private $enrolled_mk;

    private $formulir_cpm;

    private $admin;

    public function setUp(): void
    {
        parent::setUp();

        $pembayaran = Pembayaran::factory()->create();

        $this->base_route = 'evaluasi-diri.';
        $this->base_user = $pembayaran->register->user;
        $this->admin = User::where('email', 'admin@arkatama.test')->first();
        $this->giveAccess();
    }

    public function test_cant_access_the_main_tab_when_not_in_registration_schedule(): void
    {
        $this->makeRegistrationSchedulePassed();
        $this->actingAs($this->base_user)->get(route($this->base_route.'index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_cant_access_the_main_tab_when_the_formulir_not_fullfilled_yet(): void
    {
        $this->makeTheFormIsNotFUllfilled();
        $this->actingAs($this->base_user)->get(route($this->base_route.'index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_can_access_the_main_tab_when_in_registration_schedule_and_the_registration_file_fullfilled(): void
    {
        $this->actingAs($this->base_user)->get(route($this->base_route.'index'))
            ->assertOk();
    }

    public function test_cant_access_the_matakuliah_tab_when_not_in_registration_schedule(): void
    {
        $this->makeRegistrationSchedulePassed();
        $this->actingAs($this->base_user)->get(route($this->base_route.'evaluasi-mk.index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_cant_access_the_matakuliah_tab_when_the_formulir_not_fullfilled_yet(): void
    {
        $this->makeTheFormIsNotFUllfilled();
        $this->actingAs($this->base_user)->get(route($this->base_route.'evaluasi-mk.index'))
            ->assertRedirectToRoute('dashboard');
    }

    public function test_can_access_the_matakuliah_tab_when_in_registration_schedule_and_formulir_fullfilled(): void
    {
        $this->actingAs($this->base_user)->get(route($this->base_route.'evaluasi-mk.index'))
            ->assertOk();
    }

    public function test_datatable_entries_must_be_returned_and_display_matakuliah_that_enrolled(): void
    {
        $this->request(route($this->base_route.'evaluasi-mk.index'), 'GET', $this->base_user, [], after: function (TestResponse $response) {
            $response->assertSuccessful();
            $response->assertJsonFragment([
                'matkul_id' => $this->enrolled_mk[0]->matakuliah->id,
            ]);
        });
    }

    public function test_can_change_proficience_level_of_enrolled_mk()
    {
        $this->request(route($this->base_route.'evaluasi-mk.penguasaan.update', $this->enrolled_mk[0]->matakuliah->id), 'PUT', $this->base_user, [
            'tingkat_penguasaan' => 'Baik',
        ], after: function ($response) {
            $response->assertSuccessful();
            $this->assertDatabaseHas('penilaian', [
                'matkul_id' => $this->enrolled_mk[0]->matakuliah->id,
                'formulir_id' => $this->base_user->formulir->id,
                'tingkat_kemampuan' => 'Baik',
            ]);
            $this->assertDatabaseMissing('penilaian', [
                'matkul_id' => $this->enrolled_mk[0]->matakuliah->id,
                'formulir_id' => $this->base_user->formulir->id,
                'tingkat_kemampuan' => null,
            ]);
        });
    }

    public function test_can_access_the_fill_proficience_evidence_page()
    {
        $this->request(route($this->base_route.'evaluasi-mk.show', $this->enrolled_mk[0]->matakuliah->id), 'GET', $this->base_user, [], after: function ($response) {
            $response->assertSuccessful();
        });
    }

    public function test_datatable_entries_must_be_returned_and_display_cpm_of_selected_mk()
    {
        $this->request(route($this->base_route.'evaluasi-mk.show', $this->enrolled_mk[0]->matakuliah->id), 'GET', $this->base_user, [], after: function (TestResponse $response) {
            $response->assertSuccessful();
            $response->assertJsonFragment([
                'matkul_id' => $this->enrolled_mk[0]->matakuliah->id,
            ]);
        });
    }

    public function test_cant_upload_proficience_evidence_file_with_invalid_file_type()
    {
        FormulirMatakuliahCPM::where([
            'formulir_id' => $this->base_user->formulir->id,
            'matkul_id' => $this->enrolled_mk[0]->matakuliah->id,
        ])->limit(1)->get(['id'])->each(function ($item, $i) {
            $this->request(route($this->base_route.'evaluasi-mk.bukti.store', $item->id), 'POST', $this->base_user, [
                'file_pendukung' => UploadedFile::fake()->create('file.txt', 1000, 'text/plain'),
            ], after: function ($response) {
                $response->assertStatus(422);
                $response->assertJsonValidationErrors('file_pendukung');
            });
        });
    }

    public function test_cant_upload_proficience_evidence_file_with_invalid_size()
    {
        FormulirMatakuliahCPM::where([
            'formulir_id' => $this->base_user->formulir->id,
            'matkul_id' => $this->enrolled_mk[0]->matakuliah->id,
        ])->limit(1)->get(['id'])->each(function ($item, $i) {
            $this->request(route($this->base_route.'evaluasi-mk.bukti.store', $item->id), 'POST', $this->base_user, [
                'file_pendukung' => UploadedFile::fake()->create('file.txt', 1000, 'text/plain'),
            ], after: function ($response) {
                $response->assertStatus(422);
                $response->assertJsonValidationErrors('file_pendukung');
            });
        });
    }

    public function test_can_upload_proficience_evidence_file()
    {
        $cpm = $this->enrolled_mk[0]->matakuliah->cpm->first();

        $this->formulir_cpm = FormulirMatakuliahCPM::where([
            'formulir_id' => $this->base_user->formulir->id,
            'matkul_id' => $this->enrolled_mk[0]->matakuliah->id,
            'matkul_cpm_id' => $cpm->id,
        ])->limit(1)->get(['id']);

        $this->formulir_cpm->each(function ($item, $i) {
            $this->request(route($this->base_route.'evaluasi-mk.bukti.store', $item->id), 'POST', $this->base_user, [
                'file_pendukung' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
            ], after: function ($response) use ($item) {
                $response->assertSuccessful();
                $this->assertDatabaseMissing('formulir_matakuliah_cpm', [
                    'id' => $item->id,
                    'file_pendukung' => null,
                ]);
            });
        });
    }

    public function test_can_update_proficience_evidence_file()
    {
        $this->test_can_upload_proficience_evidence_file();
        $oldRecord = FormulirMatakuliahCPM::find($this->formulir_cpm[0]->id)->first();
        $this->formulir_cpm->each(function ($item, $i) use ($oldRecord) {
            $this->request(route($this->base_route.'evaluasi-mk.bukti.store', $item->id), 'POST', $this->base_user, [
                'file_pendukung' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
            ], after: function ($response) use ($item, $oldRecord) {
                $response->assertSuccessful();
                $this->assertDatabaseMissing('formulir_matakuliah_cpm', [
                    'id' => $item->id,
                    'file_pendukung' => $oldRecord->file_pendukung,
                ]);
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

    private function makeTheFormIsNotFUllfilled()
    {
        Formulir::where('id', $this->base_user->formulir->id)->update([
            'nama_lengkap' => 'Ahmad Basofi RSWT',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Hareudang, no.20 Bandung',
            'jenis_kelamin' => 'L',
            'provinsi_id' => 32,
            'kabupaten_kota_id' => null,
            'pendidikan_terakhir' => null,
            'nama_instansi_pendidikan' => null,
            'jurusan' => null,
            'tahun_lulus' => null,
        ]);
        $this->reset_enrolled_matakuliah();
    }

    private function giveAccess()
    {

        $registration = $this->base_user->register;
        $formulir = $this->base_user->formulir;
        ProdiPilihan::where([
            'tahun_ajaran_id' => $registration->tahun_ajaran_id,
            'prodi_id' => $registration->prodi_id,
        ])->update([
            'tanggal_mulai_pendaftaran' => now()->addDay(-30),
            'tanggal_selesai_pendaftaran' => now()->addDays(-10),
            'tanggal_mulai_administrasi' => now()->addDay(-10),
            'tanggal_selesai_administrasi' => now()->addDays(30),
        ]);

        $this->actingAs($this->admin);

        SyaratPendaftaran::where('prodi_id', $registration->prodi_id)->each(function ($syarat) use ($formulir) {
            FormulirBerkasPersyaratan::create([
                'formulir_id' => $formulir->id,
                'persyaratan_id' => $syarat->id,
                'file_pendukung' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
                'keterangan' => 'File Description',
            ]);
        });
        Formulir::where('id', $formulir->id)->update([
            'nama_lengkap' => 'Ahmad Basofi RSWT',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1999-12-12',
            'status_pernikahan' => 'lajang',
            'kebangsaan' => 'WNI',
            'alamat' => 'Jl. Hareudang, no.20 Bandung',
            'jenis_kelamin' => 'L',
            'provinsi_id' => 32,
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'nama_instansi_pendidikan' => 'SMAN 1 Bandung',
            'tahun_lulus' => '2017',
            'kabupaten_kota_id' => 100,
            'kecamatan_id' => 100,
            'kode_pos' => '40132',
            'nama_kantor' => 'PT. Basofi',
            'alamat_kantor' => 'Jl. Pisang Coklat, no.10 Bandung',
            'telepon_kantor' => '022-1234567',
            'jabatan' => 'Staff IT',
            'status_pekerjaan' => 'pegawai tetap',
            'jurusan' => 'IPA',
        ]);

        $this->seed(MatakuliahSettingSeeder::class);
        $this->enrolled_mk = MatakuliahSetting::with(['matakuliah.cpm'])->limit(1)->get();
        $this->request(route('formulir-f02.matakuliah.store'), 'POST', $this->base_user, [
            'mk_'.$this->enrolled_mk[0]->matakuliah->id => '1',
        ], after: function ($response) {
            $response->assertSuccessful();
        });
    }

    public function reset_enrolled_matakuliah()
    {
        FormulirMatakuliahCPM::where('formulir_id', $this->base_user->formulir->id)->forceDelete();
    }
}
