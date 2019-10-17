<?php

use Illuminate\Database\Seeder;
use App\Larajs\Permission as LarajsPermission;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class SetupRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		foreach (LarajsPermission::roles() as $role) {
			Role::findOrCreate($role, 'api');
		}

		foreach (LarajsPermission::permissions() as $permission) {
			Permission::findOrCreate($permission, 'api');
		}
    }
}
