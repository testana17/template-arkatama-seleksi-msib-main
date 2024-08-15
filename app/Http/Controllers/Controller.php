<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $modules = [];

    protected $actions = [];

    public function __construct()
    {
        $mainMiddleware = null;
        $unparsedMainMiddleware = null;
        $middleware = [];
        if (count($this->modules) > 0) {
            foreach ($this->modules as $module) {
                array_push($middleware, "permission:$module");
            }
            $unparsedMainMiddleware = end($this->modules);
            $mainMiddleware = end($middleware);
            $this->middleware("$mainMiddleware-read")->only($this->actions['read'] ?? ['show', 'edit']);
            $this->middleware("$mainMiddleware-create")->only($this->actions['create'] ?? ['store', 'create']);
            $this->middleware("$mainMiddleware-update")->only($this->actions['update'] ?? ['update', 'edit']);
            $this->middleware("$mainMiddleware-delete")->only($this->actions['delete'] ?? ['destroy']);
        }
        $this->middleware($middleware);

        view()->share('globalModule', $mainMiddleware ? [
            'create' => "$unparsedMainMiddleware-create",
            'read' => "$unparsedMainMiddleware-read",
            'update' => "$unparsedMainMiddleware-update",
            'delete' => "$unparsedMainMiddleware-delete",
        ] : null);
    }
}
