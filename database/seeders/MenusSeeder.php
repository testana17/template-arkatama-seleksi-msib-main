<?php

namespace Database\Seeders;

use App\Models\Setting\Menus;
use Illuminate\Database\Seeder;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        $json = file_get_contents(public_path('json/menu.json'));

        $menus = json_decode($json, true);

        foreach ($menus as $menu) {
            $root = Menus::create(collect($menu)->except('childrens')->toArray());
            $this->insertChildrens($menu, $root);
        }

        // set menu with name CPM RPL is_active = '0'
        Menus::where('name', 'CPM RPL')->update(['is_active' => '0']);
    }

    public function insertChildrens(array $menuData, Menus $rootMenu)
    {
        if (! empty($menuData['childrens'])) {
            foreach ($menuData['childrens'] as $children) {
                $children['parent_id'] = $rootMenu->id;
                $res = Menus::create(collect($children)->except('childrens')->toArray());
                if ($children['childrens']) {
                    $this->insertChildrens($children, $res);
                }
            }
        }
    }
}
