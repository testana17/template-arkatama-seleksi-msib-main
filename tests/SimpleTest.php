<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class SimpleTest extends TestCase
{
    use CreatesApplication,
        DatabaseTransactions;

    protected $user;

    protected $payload;

    protected $route;

    protected $table;

    protected $model;

    protected $routeParam = 'id';

    private function getPayload(array $changePayload = [])
    {
        $payload = $this->payload;
        foreach ($changePayload as $key => $value) {
            if ($value === null) {
                unset($payload[$key]);
            } else {
                $payload[$key] = $value;
            }
        }

        return $payload;
    }

    protected function testCreate(array $changePayload = [], int $status = 200, $user = null, array $assertDbHasExcept = [], array $routeParams = [])
    {
        $payload = $this->getPayload($changePayload);
        $result = $this->actingAs($user ?? $this->user)
            ->postJson(route($this->route.'store', $routeParams), $payload)
            ->assertStatus($status);
        if ($status === 200 || $status === 201) {
            foreach ($assertDbHasExcept as $key) {
                unset($payload[$key]);
            }
            $this->assertDatabaseHas($this->table, $payload);
        }

        return $result;
    }

    protected function testUpdate(array $changePayload = [], int $status = 200, $user = null, array $assertDbHasExcept = [], array $routeParams = [])
    {
        $params = array_merge([$this->routeParam => $this->model::first()->id], $routeParams);
        $payload = $this->getPayload($changePayload);
        $result = $this->actingAs($user ?? $this->user)
            ->putJson(route($this->route.'update', $params), $payload)
            ->assertStatus($status);
        if ($status === 200) {
            foreach ($assertDbHasExcept as $key) {
                unset($payload[$key]);
            }
            $this->assertDatabaseHas($this->table, $payload);
        }

        return $result;
    }

    protected function testDelete(bool $force = false, array $routeParams = [])
    {
        $params = array_merge([$this->routeParam => $this->model::first()->id], $routeParams);
        $res = $this->actingAs($this->user)
            ->deleteJson(route($this->route.'destroy', $params));

        if ($force) {
            $res->assertStatus(200);
        } else {
            $res->assertSeeText('terkait')
                ->assertStatus(400);
        }
    }

    protected function testRestore(array $routeParams = [])
    {
        $model = $this->model::first();
        $model->deleted_at = now();
        $model->save();

        $model = $this->model::onlyTrashed()->first();
        $model = $model->toArray();
        unset($model['deleted_at'], $model['created_at'], $model['updated_at']);

        $params = array_merge([$this->routeParam => $model['id']], $routeParams);

        $this->actingAs($this->user)
            ->putJson(route($this->route.'restore', $params))
            ->assertStatus(200);
        $this->assertDatabaseHas($this->table, $model);
    }
}
