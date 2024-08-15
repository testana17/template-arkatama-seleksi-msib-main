<?php

namespace Tests;

use App\Models\User;
use Closure;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function request(string $uri, string $method = 'POST', ?User $user = null, array $payload = [], ?Closure $after = null)
    {
        if ($user) {
            $requestInstance = $this->actingAs($user);
        } else {
            $requestInstance = $this;
        }
        $response = $requestInstance->json($method, $uri, $payload, ['Accept' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest']);
        if ($after) {
            $after($response);
        }

        return $response;
    }
}
