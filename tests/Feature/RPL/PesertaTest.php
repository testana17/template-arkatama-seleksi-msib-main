<?php

namespace Tests\Feature\RPL;

use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\Register;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\SimpleTest;

class PesertaTest extends SimpleTest
{
    protected $route = 'rpl.peserta.';

    protected $routeParam_peserta = 'peserta';

    protected $routeParam_berkas = 'berkas';

    protected $status_administrasi_proposed;

    protected $status_administrasi_approved;

    protected $status_administrasi_revised;

    protected $status_administrasi_rejected;

    protected $berkas_wait;

    protected $berkas_valid;

    protected $berkas_not_valid;

    public function setupStatusAdministrasi()
    {
        $register = Register::first();
        $prodi = ProgramStudi::first();

        $this->status_administrasi_proposed = Formulir::create([
            'register_id' => $register->id,
            'tahun_ajaran_id' => $register->tahun_ajaran_id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Sung Jinwoo Proposed',
            'email' => 'sungjinwooproposed@arkatama.test',
            'nomor_telepon' => '081234567890',
            'tempat_lahir' => 'Seoul',
            'tanggal_lahir' => '1994-07-25',
            'jenis_kelamin' => 'L',
            'kebangsaan' => 'WNA',
            'status_pernikahan' => 'lajang',
            'alamat' => 'Seoul',
            'kode_pos' => '12345',
            'nama_kantor' => 'Hunter Guild',
            'alamat_kantor' => 'Seoul',
            'jabatan' => 'pegawai tetap',
            'pendidikan_terakhir' => 'S1',
            'nama_instansi_pendidikan' => 'Universitas Seoul',
            'jurusan' => 'Teknik Informatika',
            'tahun_lulus' => '2016',
            'status_administrasi' => 'PROPOSED',
            'keterangan' => null,
            'status_kelulusan' => null,
            'pilihan_prodi_id' => $prodi->id,
            'provinsi_id' => 1,
            'kabupaten_kota_id' => 1,
            'kecamatan_id' => 1,
        ]);

        $this->status_administrasi_approved = Formulir::create([
            'register_id' => $register->id,
            'tahun_ajaran_id' => $register->tahun_ajaran_id,
            'nik' => '1234567890123457',
            'nama_lengkap' => 'Sung Jinwoo Approved',
            'email' => 'sungjinwooapproved@arkatama.test',
            'nomor_telepon' => '081234567890',
            'tempat_lahir' => 'Seoul',
            'tanggal_lahir' => '1994-07-25',
            'jenis_kelamin' => 'L',
            'kebangsaan' => 'WNA',
            'status_pernikahan' => 'lajang',
            'alamat' => 'Seoul',
            'kode_pos' => '12345',
            'nama_kantor' => 'Hunter Guild',
            'alamat_kantor' => 'Seoul',
            'jabatan' => 'pegawai tetap',
            'pendidikan_terakhir' => 'S1',
            'nama_instansi_pendidikan' => 'Universitas Seoul',
            'jurusan' => 'Teknik Informatika',
            'tahun_lulus' => '2016',
            'status_administrasi' => 'APPROVED',
            'keterangan' => null,
            'status_kelulusan' => null,
            'pilihan_prodi_id' => $prodi->id,
            'provinsi_id' => 1,
            'kabupaten_kota_id' => 1,
            'kecamatan_id' => 1,
        ]);

        $this->status_administrasi_revised = Formulir::create([
            'register_id' => $register->id,
            'tahun_ajaran_id' => $register->tahun_ajaran_id,
            'nik' => '1234567890123458',
            'nama_lengkap' => 'Sung Jinwoo Revised',
            'email' => 'sungjinwoorevised@arkatama.test',
            'nomor_telepon' => '081234567890',
            'tempat_lahir' => 'Seoul',
            'tanggal_lahir' => '1994-07-25',
            'jenis_kelamin' => 'L',
            'kebangsaan' => 'WNA',
            'status_pernikahan' => 'lajang',
            'alamat' => 'Seoul',
            'kode_pos' => '12345',
            'nama_kantor' => 'Hunter Guild',
            'alamat_kantor' => 'Seoul',
            'jabatan' => 'pegawai tetap',
            'pendidikan_terakhir' => 'S1',
            'nama_instansi_pendidikan' => 'Universitas Seoul',
            'jurusan' => 'Teknik Informatika',
            'tahun_lulus' => '2016',
            'status_administrasi' => 'REVISED',
            'keterangan' => null,
            'status_kelulusan' => null,
            'pilihan_prodi_id' => $prodi->id,
            'provinsi_id' => 1,
            'kabupaten_kota_id' => 1,
            'kecamatan_id' => 1,
        ]);

        $this->status_administrasi_rejected = Formulir::create([
            'register_id' => $register->id,
            'tahun_ajaran_id' => $register->tahun_ajaran_id,
            'nik' => '1234567890123459',
            'nama_lengkap' => 'Sung Jinwoo Rejected',
            'email' => 'sungjinwoorejected@arkatama.test',
            'nomor_telepon' => '081234567890',
            'tempat_lahir' => 'Seoul',
            'tanggal_lahir' => '1994-07-25',
            'jenis_kelamin' => 'L',
            'kebangsaan' => 'WNA',
            'status_pernikahan' => 'lajang',
            'alamat' => 'Seoul',
            'kode_pos' => '12345',
            'nama_kantor' => 'Hunter Guild',
            'alamat_kantor' => 'Seoul',
            'jabatan' => 'pegawai tetap',
            'pendidikan_terakhir' => 'S1',
            'nama_instansi_pendidikan' => 'Universitas Seoul',
            'jurusan' => 'Teknik Informatika',
            'tahun_lulus' => '2016',
            'status_administrasi' => 'REJECTED',
            'keterangan' => null,
            'status_kelulusan' => null,
            'pilihan_prodi_id' => $prodi->id,
            'provinsi_id' => 1,
            'kabupaten_kota_id' => 1,
            'kecamatan_id' => 1,
        ]);
    }

    public function setupBerkas()
    {
        $this->berkas_wait = FormulirBerkasPersyaratan::create([
            'formulir_id' => $this->status_administrasi_proposed->id,
            'persyaratan_id' => SyaratPendaftaran::first()->id,
            'file_pendukung' => UploadedFile::fake()->create('berkas-menunggu-validasi.pdf', 100, 'application/pdf'),
            'is_valid' => '2',
            'keterangan' => '',
        ]);

        $this->berkas_valid = FormulirBerkasPersyaratan::create([
            'formulir_id' => $this->status_administrasi_proposed->id,
            'persyaratan_id' => SyaratPendaftaran::first()->id,
            'file_pendukung' => UploadedFile::fake()->create('berkas-menunggu-validasi.pdf', 100, 'application/pdf'),
            'is_valid' => '1',
            'keterangan' => '',
        ]);

        $this->berkas_not_valid = FormulirBerkasPersyaratan::create([
            'formulir_id' => $this->status_administrasi_proposed->id,
            'persyaratan_id' => SyaratPendaftaran::first()->id,
            'file_pendukung' => UploadedFile::fake()->create('berkas-menunggu-validasi.pdf', 100, 'application/pdf'),
            'is_valid' => '0',
            'keterangan' => '',
        ]);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->actingAs($this->user);
        $this->setupStatusAdministrasi();
        $this->setupBerkas();
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

    public function test_can_show_peserta(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route.'show', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]));
        $response->assertStatus(200);
    }

    public function test_can_update_is_valid_from_proposed_to_wait(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), [
                'is_valid' => '2',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(200);
    }

    public function test_can_update_is_valid_from_proposed_to_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), [
                'is_valid' => '1',
            ]);
        $response->assertStatus(200);
    }

    public function test_can_update_is_valid_from_proposed_to_not_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), [
                'is_valid' => '0',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(200);
    }

    public function test_can_block_is_valid_from_valid_to_wait(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_valid->id]), [
                'is_valid' => '2',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_is_valid_from_valid_to_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_valid->id]), [
                'is_valid' => '1',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_is_valid_from_valid_to_not_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_valid->id]), [
                'is_valid' => '0',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_is_valid_from_not_valid_to_wait(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_not_valid->id]), [
                'is_valid' => '2',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_is_valid_from_not_valid_to_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_not_valid->id]), [
                'is_valid' => '1',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_is_valid_from_not_valid_to_not_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_not_valid->id]), [
                'is_valid' => '0',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_update_status_administrasi_from_proposed_to_proposed(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), [
                'status_administrasi' => 'PROPOSED',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(200);
    }

    public function test_can_update_status_administrasi_from_proposed_to_approved(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), [
                'status_administrasi' => 'APPROVED',
            ]);
        $response->assertStatus(200);
    }

    public function test_can_update_status_administrasi_from_proposed_to_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), [
                'status_administrasi' => 'REJECTED',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(200);
    }

    public function test_can_block_status_administrasi_from_approved_to_proposed(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_approved->id]), [
                'status_administrasi' => 'PROPOSED',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_status_administrasi_from_approved_to_approved(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_approved->id]), [
                'status_administrasi' => 'APPROVED',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_status_administrasi_from_approved_to_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_approved->id]), [
                'status_administrasi' => 'REJECTED',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_status_administrasi_from_rejected_to_proposed(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_rejected->id]), [
                'status_administrasi' => 'PROPOSED',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_status_administrasi_from_rejected_to_approved(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_rejected->id]), [
                'status_administrasi' => 'APPROVED',
            ]);
        $response->assertStatus(400);
    }

    public function test_can_block_status_administrasi_from_rejected_to_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_rejected->id]), [
                'status_administrasi' => 'REJECTED',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(400);
    }

    public function test_response_requirement_fields_for_update_is_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), []);
        $response->assertStatus(302);
    }

    public function test_can_block_empty_fields_for_update_is_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), [
                'is_valid' => '',
                'keterangan' => '',
            ]);
        $response->assertStatus(302);
    }

    public function test_can_block_only_is_valid_fields_for_update_is_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), [
                'is_valid' => '',
            ]);
        $response->assertStatus(302);
    }

    public function test_can_block_only_keterangan_fields_for_update_is_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), [
                'keterangan' => '',
            ]);
        $response->assertStatus(302);
    }

    public function test_response_requirement_fields_for_update_status_administrasi(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), []);
        $response->assertStatus(302);
    }

    public function test_can_block_empty_fields_for_update_status_administrasi(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), [
                'status_administrasi' => '',
                'keterangan' => '',
            ]);
        $response->assertStatus(302);
    }

    public function test_can_block_only_status_administrasi_fields_for_update_status_administrasi(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), [
                'status_administrasi' => '',
            ]);
        $response->assertStatus(302);
    }

    public function test_can_block_only_keterangan_fields_for_update_status_administrasi(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), [
                'keterangan' => '',
            ]);
        $response->assertStatus(302);
    }

    public function test_can_block_is_valid_field_with_invalid_value_for_update_is_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-validasi', [$this->routeParam_berkas => $this->berkas_wait->id]), [
                'is_valid' => '3',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(302);
    }

    public function test_can_block_status_administrasi_field_with_invalid_value_for_update_status_administrasi(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route($this->route.'update-administrasi', [$this->routeParam_peserta => $this->status_administrasi_proposed->id]), [
                'status_administrasi' => 'INVALID',
                'keterangan' => 'Keterangan',
            ]);
        $response->assertStatus(302);
    }
}
