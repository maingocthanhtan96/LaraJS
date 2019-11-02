<?php

use Illuminate\Database\Seeder;
use App\Larajs\Permission as LarajsPermission;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(
            [
                'name' => LarajsPermission::ROLE_ADMIN,
                'guard_name' => 'api',
                'description' => 'Super Administrator. Have access and full permission to all pages.'
            ]
        );
        Role::create(
            [
                'name' => LarajsPermission::ROLE_MANAGER,
                'description' => 'Just a manager. Can you manage',
            ]
        );
        Role::create(
            [
                'name' => LarajsPermission::ROLE_VISITOR,
                'description' => 'Just a visitor. Can only see the home page and the document page',
            ]
        );
        Role::create(
            [
                'name' => LarajsPermission::ROLE_CREATOR,
                'description' => 'Just a creator. Can only create page',
            ]
        );
        Role::create(
            [
                'name' => LarajsPermission::ROLE_EDITOR,
                'description' => 'Just a editor. Can only edit page',
            ]
        );
        Role::create(
            [
                'name' => LarajsPermission::ROLE_DELETER,
                'description' => 'Just a deleter. Can only delete page',
            ]
        );
        Role::create(
            [
                'name' => LarajsPermission::ROLE_DEVELOPER,
                'description' => 'Just a developer. Some permissions(visit, create, edit, delete, develop)',
            ]
        );
    }
}
