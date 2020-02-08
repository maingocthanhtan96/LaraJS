<?php

use Illuminate\Database\Seeder;
use App\Larajs\Acl;
use App\Models\Role;
use App\Models\User;

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
        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(Acl::ROLE_MANAGER);
        $visitorRole = Role::findByName(Acl::ROLE_VISITOR);
        $creatorRole = Role::findByName(Acl::ROLE_CREATOR);
        $editorRole = Role::findByName(Acl::ROLE_EDITOR);
        $deleterRole = Role::findByName(Acl::ROLE_DELETER);
        $developerRole = Role::findByName(Acl::ROLE_DEVELOPER);
        // Setup basic role
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $visitor->syncRoles($visitorRole);
        $creator->syncRoles($creatorRole);
        $editor->syncRoles($editorRole);
        $deleter->syncRoles($deleterRole);
        $developer->syncRoles($developerRole);

        // Setup basic permission
        $adminRole->givePermissionTo(Acl::permissions());
        $managerRole->givePermissionTo([
            Acl::PERMISSION_PERMISSION_MANAGE,
            Acl::PERMISSION_VISIT,
            Acl::PERMISSION_CREATE,
            Acl::PERMISSION_EDIT,
            Acl::PERMISSION_DELETE,
            Acl::PERMISSION_VIEW_MENU_ROLE_PERMISSION
        ]);
        $visitorRole->givePermissionTo(Acl::PERMISSION_VISIT);
        $creatorRole->givePermissionTo(Acl::PERMISSION_CREATE);
        $editorRole->givePermissionTo(Acl::PERMISSION_EDIT);
        $deleterRole->givePermissionTo(Acl::PERMISSION_DELETE);
        $developerRole->givePermissionTo([
            Acl::PERMISSION_VISIT,
            Acl::PERMISSION_CREATE,
            Acl::PERMISSION_EDIT,
            Acl::PERMISSION_DELETE,
            Acl::PERMISSION_DEVELOP
        ]);

        $faker = Faker\Factory::create();
        $limit = 100;
        for ($i = 0; $i < $limit; $i++) {
            $userFaker = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'avatar' => $faker->imageUrl($width = 150, $height = 150),
                'password' => 'secret',
                'remember_token' => \Illuminate\Support\Str::random(10)
                //{{SEEDER_NOT_DELETE_THIS_LINE}}
            ]);
            $roleName = $faker->randomElement([
                Acl::ROLE_VISITOR,
                Acl::ROLE_CREATOR,
                Acl::ROLE_EDITOR,
                Acl::ROLE_DELETER
            ]);
            $userFaker->syncRoles($roleName);
        }
    }
}
