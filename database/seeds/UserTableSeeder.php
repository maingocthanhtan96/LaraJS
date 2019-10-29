<?php

use Illuminate\Database\Seeder;
use App\Larajs\Permission as LarajsPermission;
use App\Models\Role;
use \App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Thanh Tan',
            'email' => 'admin@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'admin123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'manager123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'visitor123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
        $creator = User::create([
            'name' => 'Creator',
            'email' => 'creator@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'creator123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'editor123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
        $deleter = User::create([
            'name' => 'Deleter',
            'email' => 'deleter@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'deleter123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
        $developer = User::create([
            'name' => 'Developer',
            'email' => 'developer@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'developer123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);

        // search role
        $adminRole = Role::findByName(LarajsPermission::ROLE_ADMIN);
        $managerRole = Role::findByName(LarajsPermission::ROLE_MANAGER);
        $visitorRole = Role::findByName(LarajsPermission::ROLE_VISITOR);
        $creatorRole = Role::findByName(LarajsPermission::ROLE_CREATOR);
        $editorRole = Role::findByName(LarajsPermission::ROLE_EDITOR);
        $deleterRole = Role::findByName(LarajsPermission::ROLE_DELETER);
        $developerRole = Role::findByName(LarajsPermission::ROLE_DEVELOPER);
        // Setup basic role
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $visitor->syncRoles($visitorRole);
        $creator->syncRoles($creatorRole);
        $editor->syncRoles($editorRole);
        $deleter->syncRoles($deleterRole);
        $developer->syncRoles($developerRole);

        // Setup basic permission
        $adminRole->givePermissionTo(LarajsPermission::permissions());
        $managerRole->givePermissionTo([
            LarajsPermission::PERMISSION_VISIT,
            LarajsPermission::PERMISSION_CREATE,
            LarajsPermission::PERMISSION_EDIT,
            LarajsPermission::PERMISSION_DELETE,
        ]);
        $visitorRole->givePermissionTo(LarajsPermission::PERMISSION_VISIT);
        $creatorRole->givePermissionTo(LarajsPermission::PERMISSION_CREATE);
        $editorRole->givePermissionTo(LarajsPermission::PERMISSION_EDIT);
        $deleterRole->givePermissionTo(LarajsPermission::PERMISSION_DELETE);
        $developerRole->givePermissionTo([
            LarajsPermission::PERMISSION_VISIT,
            LarajsPermission::PERMISSION_CREATE,
            LarajsPermission::PERMISSION_EDIT,
            LarajsPermission::PERMISSION_DELETE,
            LarajsPermission::PERMISSION_DEVELOP,
        ]);

        $faker = Faker\Factory::create();
        $limit = 100;
        for ($i = 0; $i < $limit; $i++) {
            $userFaker = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'avatar' => $faker->imageUrl($width = 150, $height = 150),
                'password' => 'secret',
                'remember_token' => \Illuminate\Support\Str::random(10),
                //{{SEEDER_NOT_DELETE_THIS_LINE}}
            ]);
            $roleName = $faker->randomElement([
                LarajsPermission::ROLE_VISITOR,
                LarajsPermission::ROLE_CREATOR,
                LarajsPermission::ROLE_EDITOR,
                LarajsPermission::ROLE_DELETER,
            ]);
            $userFaker->syncRoles($roleName);
        }
    }
}
