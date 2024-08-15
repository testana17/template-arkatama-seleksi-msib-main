<?php

namespace Tests\Feature\Camaba;

use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirBerkasPersyaratan;
use App\Models\Rpl\SyaratPendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\CreatesApplication;
use Tests\TestCase;

class BerkasPersyaratanTest extends TestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $user;

    protected $payload;

    protected $table = 'formulir_berkas_persyaratan';

    protected $model = FormulirBerkasPersyaratan::class;

    protected $route = 'berkas-persyaratan.';

    protected $routeParam = 'id';

    public function setUp(): void
    {
        parent::setUp();

        $pembayaran = Pembayaran::factory()->create();
        $formulir = $pembayaran->register->formulir;
        $this->user = User::where('email', $pembayaran->register->email)->first();

        $this->payload = [
            'file_pendukung' => UploadedFile::fake()->create('file.pdf', 1000, 'application/pdf'),
            'persyaratan_id' => SyaratPendaftaran::where('prodi_id', $formulir->pilihan_prodi_id)->first()->id,
        ];
    }

    public function test_must_authenticated_to_access_page(): void
    {
        $response = $this->get(route($this->route.'index'));
        $response->assertStatus(302);
    }

    public function test_must_in_schedule_to_access_page(): void
    {
        $formulir = Formulir::factory()->create();
        $formulir->register->prodiPilihan->update([
            'tanggal_mulai_pendaftaran' => now()->subDays(2),
            'tanggal_selesai_administrasi' => now()->subDay(),
        ]);
        $user = User::where('email', $formulir->email)->first();
        $response = $this->actingAs($user)
            ->get(route($this->route.'index'));
        $response->assertRedirectContains(route('dashboard'));
        $response->assertStatus(302);
        $formulir->register->prodiPilihan->update([
            'tanggal_mulai_pendaftaran' => now()->subDays(2),
            'tanggal_selesai_administrasi' => now()->addDay(),
        ]);
    }

    public function test_must_already_payment_is_completed_to_access_page(): void
    {
        $formulir = Formulir::factory()->create();
        $user = User::where('email', $formulir->email)->first();
        $response = $this->actingAs($user)
            ->get(route($this->route.'index'));
        $response->assertRedirectContains(route('dashboard'));
        $response->assertStatus(302);
    }

    public function test_the_index_page_returns_successful_response_when_user_is_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route($this->route.'index'));
        $response->assertStatus(200);
    }

    public function test_can_create_berkas_persyaratan_with_valid_payload(): void
    {
        $model = new FormulirBerkasPersyaratan($this->payload);
        $response = $this->actingAs($this->user)
            ->putJson(route($this->route.'upload'), $this->payload)
            ->assertSuccessful();

        $this->assertDatabaseHas($this->table, $model->toArray());
    }

    public function test_can_update_berkas_persyaratan_with_valid_payload(): void
    {
        $this->test_can_create_berkas_persyaratan_with_valid_payload();
        $model = FormulirBerkasPersyaratan::first();
        $updatePayload = array_merge($this->payload, [
            'file_pendukung' => UploadedFile::fake()->create('update.pdf', 1000, 'application/pdf'),
        ]);
        $newModel = new FormulirBerkasPersyaratan($updatePayload);
        $this->actingAs($this->user)
            ->putJson(route($this->route.'upload', ['id' => $model->id]), $updatePayload)
            ->assertSuccessful();

        $this->assertDatabaseHas($this->table, $newModel->toArray());
        $this->assertDatabaseMissing($this->table, $model->toArray());
    }

    public function test_upload_validation_berkas_persyaratan_with_file_pendukung_required(): void
    {
        $this->payload['file_pendukung'] = null;
        $this->actingAs($this->user)
            ->putJson(route($this->route.'upload'), $this->payload)
            ->assertJsonValidationErrors('file_pendukung');
    }

    public function test_upload_validation_berkas_persyaratan_with_file_pendukung_file(): void
    {
        $this->payload['file_pendukung'] = UploadedFile::fake()->create('file.pdfs', 1000, 'text/plain');
        $this->actingAs($this->user)
            ->putJson(route($this->route.'upload'), $this->payload)
            ->assertJsonValidationErrors('file_pendukung');
    }

    public function test_upload_validation_berkas_persyaratan_with_file_pendukung_mimes(): void
    {
        $this->payload['file_pendukung'] = UploadedFile::fake()->create('file.exe', 1000, 'application/exe');
        $this->actingAs($this->user)
            ->putJson(route($this->route.'upload'), $this->payload)
            ->assertJsonValidationErrors('file_pendukung');
    }

    public function test_upload_validation_berkas_persyaratan_with_file_pendukung_max(): void
    {
        $this->payload['file_pendukung'] = UploadedFile::fake()->create('file.pdf', 3000, 'application/pdf');
        $this->actingAs($this->user)
            ->putJson(route($this->route.'upload'), $this->payload)
            ->assertJsonValidationErrors('file_pendukung');
    }

    public function test_upload_validation_berkas_persyaratan_with_persyaratan_id_exists(): void
    {
        $this->assertDatabaseHas('ref_persyaratan', ['id' => $this->payload['persyaratan_id']]);
    }
}
