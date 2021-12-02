<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class SetupRolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\ACL::roles() as $role) {
            Role::findOrCreate($role, 'api');
        }

        foreach (\ACL::permissions() as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        foreach (\ACL::menuPermissions() as $menuPermission) {
            Permission::findOrCreate($menuPermission, 'api');
        }
    }
}
