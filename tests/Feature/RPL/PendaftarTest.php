<?php

namespace Tests\Feature\RPL;

use App\Models\Rpl\Register;
use App\Models\User;
use Tests\SimpleTest;

class PendaftarTest extends SimpleTest
{
    protected $table = 'registers';

    protected $model = Register::class;

    protected $route_pendaftar = 'rpl.pendaftar.';

    protected $routeParam_pendaftar = 'pendaftar';

    protected $route_user = 'users.user-list.';

    protected $routeParam_userList = 'user_list';

    protected $routeParam_user = 'user';

    protected $pendaftar;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('email', 'admin@arkatama.test')->first();
        $this->pendaftar = User::where('email', 'not like', '%@arkatama.test')
            ->where('email', 'not like', '%@um.ac.id')
            ->first();
    }

    public function test_must_authenticated_to_access_page(): void
    {
        $response = $this->get(route($this->route_pendaftar.'index'));
        $response->assertStatus(302);
    }

    public function test_the_index_page_returns_successful_response_when_use_is_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route($this->route_pendaftar.'index'));
        $response->assertStatus(200);
    }

    public function test_datatable_entries_must_be_returned(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route($this->route_pendaftar.'index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonStructure(['data']);
    }

    public function test_can_show_reset_password(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route($this->route_user.'edit', [$this->routeParam_userList => $this->pendaftar->id]));
        $response->assertStatus(200);
    }

    public function test_can_request_reset_password(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route($this->route_user.'reset-password', [$this->routeParam_user => $this->pendaftar->id]), ['email' => $this->pendaftar->email]);
        $response->assertStatus(200);
    }

    public function test_update_validation_email(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route($this->route_user.'reset-password', [$this->routeParam_user => $this->pendaftar->id]), ['email' => 'not an email']);
        $response->assertStatus(302);
    }
}
