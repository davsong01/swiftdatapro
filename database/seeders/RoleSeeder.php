<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            'Dashboard',
            'Web Settings',
            'My Site ApiKeys',
            'Blacklist Number',
            'Order Number',
            'Staff Roles',
            'Staff Permissions',
        ];

        $permissions = [
            'role.index',
            'role.show',
            'role.edit',
            'role.update',
            'role.destroy',

            'permission.show',
            'permission.index',
            'permission.edit',
            'permission.update',
            'permission.destroy',
            'permission.create',

            'settings.edit',
            'settings.update',
            
        ];

        // RolePermission::truncate();
        foreach($menu as $key=>$value){
            RolePermission::updateOrCreate(
                [
                    'name' => $value,
                    'route' => $value,
                    'type' => 'menu',
                ],[
                'name' => $value,
                'route' => $value,
                'type' => 'menu',
                'status' => 'active',
            ]);
        }

        foreach ($permissions as $key => $value) {
            RolePermission::updateOrCreate(
                [
                    'name' => $value,
                    'route' => $value,
                    'type' => 'link',
                ],[
                'name' => $value,
                'route' => $value,
                'type' => 'link',
                'status' => 'active',
            ]);
        }

        Role::updateOrCreate(['name' => 'Admin','status' => 'active'],[
            'name' => 'Admin',
            'permissions' => "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,115,116,117,118,119,120,121",
            'status' => 'active'
        ]);
    }
}
