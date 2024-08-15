<?php

namespace Database\Seeders;

use App\Models\Setting\Access;
use App\Models\Setting\Menus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menus::all();
        $access = ['create', 'read', 'update', 'delete'];
        $permissions = [];
        $accesses = [];

        foreach ($menus as $menu) {
            $permissions[] = [
                'name' => $menu->module,
                'guard_name' => 'web',
            ];
            foreach ($access as $acc) {
                $permissions[] = [
                    'name' => $menu->module.'-'.$acc,
                    'guard_name' => 'web',
                ];
                $accesses[] = [
                    'name' => "$menu->name $acc",
                    'module' => $menu->module.'-'.$acc,
                    'menus_id' => $menu->id,
                ];
            }
        }
        Permission::insert($permissions);
        Access::insert($accesses);
    }
}
