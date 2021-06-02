<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    const LOGO = '/images/logo-tanmnt.png';
    const PASSWORD = 'Admin@123!';
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
            'avatar' => self::LOGO,
            'password' => self::PASSWORD,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@larajs.com',
            'avatar' => self::LOGO,
            'password' => self::PASSWORD,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@larajs.com',
            'avatar' => self::LOGO,
            'password' => self::PASSWORD,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        $creator = User::create([
            'name' => 'Creator',
            'email' => 'creator@larajs.com',
            'avatar' => self::LOGO,
            'password' => self::PASSWORD,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@larajs.com',
            'avatar' => self::LOGO,
            'password' => self::PASSWORD,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        $deleter = User::create([
            'name' => 'Deleter',
            'email' => 'deleter@larajs.com',
            'avatar' => self::LOGO,
            'password' => self::PASSWORD,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        $developer = User::create([
            'name' => 'Developer',
            'email' => 'developer@larajs.com',
            'avatar' => self::LOGO,
            'password' => self::PASSWORD,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // search role
        $adminRole = Role::findByName(\ACL::ROLE_ADMIN);
        $managerRole = Role::findByName(\ACL::ROLE_MANAGER);
        $visitorRole = Role::findByName(\ACL::ROLE_VISITOR);
        $creatorRole = Role::findByName(\ACL::ROLE_CREATOR);
        $editorRole = Role::findByName(\ACL::ROLE_EDITOR);
        $deleterRole = Role::findByName(\ACL::ROLE_DELETER);
        $developerRole = Role::findByName(\ACL::ROLE_DEVELOPER);
        // Setup basic role
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $visitor->syncRoles($visitorRole);
        $creator->syncRoles($creatorRole);
        $editor->syncRoles($editorRole);
        $deleter->syncRoles($deleterRole);
        $developer->syncRoles($developerRole);

        // Setup basic permission
        $managerRole->givePermissionTo([
            \ACL::PERMISSION_PERMISSION_MANAGE,
            \ACL::PERMISSION_VISIT,
            \ACL::PERMISSION_CREATE,
            \ACL::PERMISSION_EDIT,
            \ACL::PERMISSION_DELETE,
            \ACL::PERMISSION_VIEW_MENU_ROLE_PERMISSION,
        ]);
        $visitorRole->givePermissionTo(\ACL::PERMISSION_VISIT);
        $creatorRole->givePermissionTo(\ACL::PERMISSION_CREATE);
        $editorRole->givePermissionTo(\ACL::PERMISSION_EDIT);
        $deleterRole->givePermissionTo(\ACL::PERMISSION_DELETE);
        $developerRole->givePermissionTo([\ACL::PERMISSION_VISIT, \ACL::PERMISSION_CREATE, \ACL::PERMISSION_EDIT, \ACL::PERMISSION_DELETE, \ACL::PERMISSION_DEVELOP]);

        $faker = Faker\Factory::create();
        $limit = 10;
        for ($i = 0; $i < $limit; $i++) {
            $userFaker = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'avatar' => self::LOGO,
                'password' => self::PASSWORD,
                'remember_token' => \Illuminate\Support\Str::random(10),
                //{{SEEDER_NOT_DELETE_THIS_LINE}}
            ]);
            $roleName = $faker->randomElement([\ACL::ROLE_VISITOR, \ACL::ROLE_CREATOR, \ACL::ROLE_EDITOR, \ACL::ROLE_DELETER]);
            $userFaker->syncRoles($roleName);
        }
    }
}
