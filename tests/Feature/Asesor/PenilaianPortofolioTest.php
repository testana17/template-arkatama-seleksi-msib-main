<?php

namespace Tests\Feature\Asesor;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Asesor\Penilaian;
use App\Models\Cms\Timeline;
use App\Models\Master\Provinsi;
use App\Models\Rpl\Asesor;
use App\Models\Rpl\AsesorPeserta;
use App\Models\Rpl\CPM;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\CreatesApplication;
use Tests\TestCase;

class PenilaianPortofolioTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $user;

    protected $payload;

    // protected $table = '';
    protected $model = Penilaian::class;

    protected $route = 'asesor.penilaian-portfolio';

    protected $routeParam = 'penilaian';

    protected $formulir;

    protected $matakuliah;

    public function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'nilai_angka' => 80,
            'is_valid' => '1',
            'is_asli' => '1',
            'is_terkini' => '1',
            'is_cukup' => '1',
        ];

        $this->user = User::create([
            'name' => 'James Doe',
            'email' => 'jamesdoe@mail.com',
            'email_verified_at' => now()->subDay(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $this->user->assignRole('camaba');
        $register = Register::create([
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
            'prodi_pilihan_id' => ProdiPilihan::where('prodi_id', ProgramStudi::where('singkatan', 'TI')->first()->id)->first()->id,
            'kode_pendaftaran' => 'KP123456',
            'nik' => '3500171112120107',
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => (int) TahunAjaran::getCurrent()['id'],
            'asal_instansi' => 'SMAN Jakarta',
            'provinsi_id' => Provinsi::where('id', 15)->first()->id,
            'nama_lengkap' => $this->user->name,
            'email' => $this->user->email,
            'nomor_telepon' => '08123456789',
            'is_active' => '1',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $formulir = Formulir::create([
            'register_id' => $register->id,
            'tahun_ajaran_id' => $register->tahun_ajaran_id,
            'nik' => $register->nik,
            'nama_lengkap' => $register->nama_lengkap,
            'email' => $register->email,
            'nomor_telepon' => $register->nomor_telepon,
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'kebangsaan' => 'WNI',
            'status_pernikahan' => 'lajang',
            'alamat' => 'Jl. Jalan Baru',
            'kode_pos' => '12345',
            'nama_kantor' => 'Kantor Pusat',
            'alamat_kantor' => 'Jl. Kantor Pusat',
            'telepon_kantor' => '08123456789',
            'jabatan' => 'Karyawan',
            'status_pekerjaan' => 'pegawai tetap',
            'pendidikan_terakhir' => 'SMA',
            'nama_instansi_pendidikan' => 'SMAN Jakarta',
            'jurusan' => 'IPA',
            'tahun_lulus' => '2018',
            'status_administrasi' => 'SUBMITTED',
            'pilihan_prodi_id' => $register->prodi_id,
            'provinsi_id' => $register->provinsi_id,
            'kabupaten_kota_id' => 259,
            'kecamatan_id' => 3904,
        ]);
        $this->formulir = $formulir;

        $listMatakuliahFormulir = Matakuliah::where('prodi_id', $formulir->pilihan_prodi_id)->get();
        $this->matakuliah = Matakuliah::where('nama_mk', 'Pemrograman Dasar')->first()->id;

        $this->user = User::where('email', $formulir->register->email)->first();
        $this->actingAs($this->user);
        foreach ($listMatakuliahFormulir as $matakuliahFormulir) {
            CPM::where('matkul_id', $matakuliahFormulir->id)->each(function ($cpm) use ($formulir) {
                FormulirMatakuliahCPM::create([
                    'formulir_id' => $formulir->id,
                    'matkul_id' => $cpm->matkul_id,
                    'matkul_cpm_id' => $cpm->id,
                    'keterangan' => 'Keterangan',
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);

                Penilaian::create([
                    'formulir_id' => $formulir->id,
                    'matkul_id' => $cpm->matkul_id,
                    'tingkat_kemampuan' => 'Baik',
                ]);
            });
        }

        $assignedAsesor = MatakuliahAsesor::create([
            'matkul_id' => $this->matakuliah,
            'asesor_id' => Asesor::where('email', 'asesor@arkatama.test')->first()->id,
        ]);

        AsesorPeserta::create([
            'matkul_asesor_id' => $assignedAsesor->id,
            'formulir_id' => $this->formulir->id,
        ]);

        $this->formulir->update(['status_administrasi' => 'APPROVED']);

        Auth::logout();

        $this->user = User::where('email', 'asesor@arkatama.test')->first();
    }

    public function test_must_authenticated_to_access_page(): void
    {
        $response = $this->get(route($this->route));
        $response->assertStatus(302);
    }

    public function test_must_in_assessment_schedule_to_access_page(): void
    {
        Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])
            ->first()->update(['tanggal_mulai_assesmen' => now()->addDays(3)]);
        $penilaian = Penilaian::where('formulir_id', $this->formulir->id)
            ->where('matkul_id', $this->matakuliah)
            ->first();
        Auth::login($this->user);
        $penilaian->update(['tingkat_kemampuan' => null]);
        $route = route('asesor.penilaian-portfolio.nilai', ['penilaian' => $penilaian->id, 'mk' => $this->matakuliah]);
        $response = $this->actingAs($this->user)->get($route);
        $response->assertStatus(302);
    }

    public function test_penilaian_must_in_has_tingkat_kemampuan_to_access_page(): void
    {
        Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])
            ->first()->update(['tanggal_mulai_assesmen' => now()->addDays(3)]);
        $getPenilaianId = Penilaian::where('formulir_id', $this->formulir->id)
            ->where('matkul_id', $this->matakuliah)
            ->first()->id;
        $route = route('asesor.penilaian-portfolio.nilai', ['penilaian' => $getPenilaianId, 'mk' => $this->matakuliah]);
        $response = $this->actingAs($this->user)->get($route);
        $response->assertStatus(302);
    }

    public function test_can_access_page_when_in_assessmen_schedule(): void
    {
        Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])
            ->first()->update(['tanggal_mulai_assesmen' => now()->subDay()]);
        $getPenilaianId = Penilaian::where('formulir_id', $this->formulir->id)
            ->where('matkul_id', $this->matakuliah)
            ->first()->id;
        $route = route('asesor.penilaian-portfolio.nilai', ['penilaian' => $getPenilaianId, 'mk' => $this->matakuliah]);
        $response = $this->actingAs($this->user)->get($route);
        $response->assertStatus(200);
    }

    public function test_penilaian(): void
    {

        $this->user = User::where('email', 'asesor@arkatama.test')->first();
        $this->actingAs($this->user);

        $getPenilaianId = Penilaian::where('formulir_id', $this->formulir->id)
            ->where('matkul_id', $this->matakuliah)
            ->first()->id;

        $routes = route('asesor.penilaian-portfolio.update', ['penilaian' => $getPenilaianId, 'mk' => $this->matakuliah]);

        $response = $this->putJson($routes, $this->payload);
        $response->assertStatus(200);
    }
}
