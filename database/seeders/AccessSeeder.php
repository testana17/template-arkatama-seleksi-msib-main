<?php

namespace Database\Seeders;

use App\Models\Setting\Menus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AccessSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all();

        if (file_exists(public_path('json/access.json'))) {
            $accessess = json_decode(file_get_contents(public_path('json/access.json')), true);
            foreach ($roles as $role) {
                $relatedAccess = array_values(array_filter($accessess, fn ($access) => $access['role'] === $role->name));
                if ($relatedAccess) {
                    collect($relatedAccess[0]['permissions'])->chunk(100)->each(function ($permissions) use ($role) {
                        $role->givePermissionTo($permissions);
                    });
                }
            }
        } else {
            $baseAccess = ['create', 'read', 'update', 'delete'];
            $accesses = [];

            $menus = Menus::all();
            foreach ($menus as $menu) {
                $accesses[] = "$menu->module";
                foreach ($baseAccess as $access) {
                    $accesses[] = "$menu->module-$access";
                }
            }

            foreach ($roles as $role) {
                $role->givePermissionTo($accesses);
            }
        }
    }
}
