<?php

use Illuminate\Database\Seeder;
use App\Larajs\Permission as LarajsPermission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create(
            [
                'name' => LarajsPermission::ROLE_ADMIN,
                'guard_name' => 'api',
                'description' => 'Super Administrator. Have access and full permission to all pages.'
            ]
        );
        \App\Models\Role::create(
            [
                'name' => LarajsPermission::ROLE_MANAGER,
                'description' => 'Just a manager. Can you manage',
            ]
        );
        \App\Models\Role::create(
            [
                'name' => LarajsPermission::ROLE_VISITOR,
                'description' => 'Just a visitor. Can only see the home page and the document page',
            ]
        );
        \App\Models\Role::create(
            [
                'name' => LarajsPermission::ROLE_CREATOR,
                'description' => 'Just a creator. Can only create page',
            ]
        );
        \App\Models\Role::create(
            [
                'name' => LarajsPermission::ROLE_EDITOR,
                'description' => 'Just a editor. Can only edit page',
            ]
        );
    }
}
