<?php

namespace Tests;

use Closure;

abstract class CRUDTestCase extends TestCase
{
    protected $base_route;

    protected $base_model;

    protected $base_user;

    protected function setBaseUser($user)
    {
        $this->base_user = $user;
    }

    protected function setBaseRoute($route)
    {
        $this->base_route = $route;
    }

    protected function setBaseModel($model)
    {
        if ($model instanceof \Illuminate\Database\Eloquent\Model) {
            $this->base_model = $model;
        } else {
            $this->base_model = resolve($model);
        }
    }

    protected function testAccess($route, $method, $user, $status = 200, $params = null)
    {
        if ($user) {
            $response = $this->actingAs($user)->$method(route($route, $params));
        } else {
            $response = $this->$method(route($route, $params));
        }
        $response->assertStatus($status);

        return $response;
    }

    protected function testShowDatatable($route = null, $user = null)
    {
        $user = $this->base_user ?? $user;
        $route = $this->base_route ? "{$this->base_route}.index" : $route;
        $response = $this->actingAs($user)
            ->getJson(route($route), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);

        return $response;
    }

    protected function testCreate($attributes = [], $model = null, $route = null, $user = null, $status = 201)
    {
        $user = $this->base_user ?? $user;
        $model = $this->base_model ?? $model;
        $route = $this->base_route ? "{$this->base_route}.store" : $route;
        $model = new $model($attributes);
        $response = $this->actingAs($user ?? $this->base_user)
            ->postJson(route($route), $attributes);
        $response->assertStatus($status);
        if ($status != 201) {
            return $response;
        }
        $this->assertDatabaseHas($model->getTable(), $model->toArray());
        $latestModel = $model::latest()->first();

        return [$response, $latestModel];
    }

    protected function testUpdate($model, $attributes = [], $route = null, $user = null, $status = 200, ?Closure $onSuccess = null)
    {
        $user = $this->base_user ?? $user;
        $route = $this->base_route ? "{$this->base_route}.update" : $route;

        $response = $this->actingAs($user)
            ->putJson(route($route, $model->id), $attributes);
        $response->assertStatus($status);
        if ($status != 200) {
            return $response;
        }
        if ($onSuccess) {
            $onSuccess($response);

            return;
        }
        $this->assertDatabaseHas($model->getTable(), [
            $model->getKeyName() => $model->{$model->getKeyName()},
            ...$attributes,
        ]);

        $this->assertDatabaseMissing($model->getTable(), $model->toArray());

        $latestModel = $model::latest('updated_at')->first();

        return [$response, $latestModel];
    }

    protected function testDelete($model = null, $route = null, $user = null, $status = 200, $isSoftDeleting = false)
    {
        $user = $this->base_user ?? $user;
        $route = $this->base_route ? "{$this->base_route}.destroy" : $route;

        $response = $this->actingAs($user)
            ->deleteJson(route($route, $model->id));
        $response->assertStatus($status);
        if ($status != 200) {
            return $response;
        }

        if ($isSoftDeleting) {
            $this->assertSoftDeleted($model->getTable(), ['id' => $model->id]);
        } else {
            $this->assertDatabaseMissing($model->getTable(), ['id' => $model->id]);
        }

        return $response;
    }

    protected function testRestore($model = null, $route = null, $status = 200, $user = null)
    {
        $user = $this->base_user ?? $user;
        $route = $this->base_route ? "{$this->base_route}.restore" : $route;
        if ($model?->id == null) {
            $model = $this->base_model->latest()->first();
        }

        $response = $this->actingAs($user)
            ->putJson(route($route, $model->id));
        $response->assertStatus($status);
        if ($status != 200) {
            return $response;
        }
        $this->assertDatabaseHas($model->getTable(), $this->base_model->toArray());

        return $response;
    }
}
