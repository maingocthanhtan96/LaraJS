<?php

use Illuminate\Database\Seeder;
use App\Larajs\Acl;
use App\Models\Role;
use App\Models\Permission;

class SetupRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Acl::roles() as $role) {
            Role::findOrCreate($role, 'api');
        }

        foreach (Acl::permissions() as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        foreach (Acl::menuPermissions() as $menuPermission) {
            Permission::findOrCreate($menuPermission, 'api');
        }
    }
}
