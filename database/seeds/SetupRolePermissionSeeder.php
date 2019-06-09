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
		$adminRole = Role::findByName(LarajsPermission::ROLE_ADMIN);
		$userRole = Role::findByName(LarajsPermission::ROLE_USER);

		foreach (LarajsPermission::permissions() as $permission) {
			Permission::findOrCreate($permission, 'api');
		}

		// Setup basic permission
		$adminRole->givePermissionTo(LarajsPermission::permissions());
		$userRole->givePermissionTo([
			LarajsPermission::PERMISSION_USER_MANAGE,
		]);

		User::find(1)->assignRole(LarajsPermission::ROLE_ADMIN);
		User::find(2)->assignRole(LarajsPermission::ROLE_USER);

		/** @var \App\User[] $users */
		$users = User::whereNotIn('id' ,[1,2])->get();
		foreach ($users as $user) {
			$user->syncRoles(LarajsPermission::ROLE_VISITOR);
		}
    }
}
