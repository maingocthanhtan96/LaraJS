<?php

use Illuminate\Database\Seeder;
use App\Larajs\Permission as LarajsPermission;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 10;

		$admin = \App\Models\User::create([
			'name' => 'Thanh Tan',
			'email' => 'admin@larajs.com',
			'avatar' => '/images/avatar/logo-tanmnt.png',
			'password' => 'admin123',
			'remember_token' => \Illuminate\Support\Str::random(10)
		]);
        $manager = \App\Models\User::create([
            'name' => 'Manager',
            'email' => 'manager@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'manage123',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);

        $visitor = \App\Models\User::create([
            'name' => 'Visitor',
            'email' => 'visitor@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'larajs',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);

        $creator = \App\Models\User::create([
            'name' => 'Creator',
            'email' => 'creator@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'larajs',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);

        $editor = \App\Models\User::create([
            'name' => 'Editor',
            'email' => 'editor@larajs.com',
            'avatar' => '/images/avatar/logo-tanmnt.png',
            'password' => 'larajs',
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
        // search role
        $adminRole = Role::findByName(LarajsPermission::ROLE_ADMIN);
        $managerRole = Role::findByName(LarajsPermission::ROLE_MANAGER);
        $visitorRole = Role::findByName(LarajsPermission::ROLE_VISITOR);
        $creatorRole = Role::findByName(LarajsPermission::ROLE_CREATOR);
        $editorRole = Role::findByName(LarajsPermission::ROLE_EDITOR);
        // Setup basic role
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $visitor->syncRoles($visitorRole);
        $creator->syncRoles($creatorRole);
        $editor->syncRoles($editorRole);

        // Setup basic permission
        $adminRole->givePermissionTo(LarajsPermission::permissions());
        $managerRole->givePermissionTo([
            LarajsPermission::PERMISSION_MANAGE_VISIT,
            LarajsPermission::PERMISSION_MANAGE_CREATE,
            LarajsPermission::PERMISSION_MANAGE_EDIT,
        ]);
        $visitorRole->givePermissionTo(LarajsPermission::PERMISSION_MANAGE_VISIT);
        $creator->givePermissionTo(LarajsPermission::PERMISSION_MANAGE_CREATE);
        $editor->givePermissionTo(LarajsPermission::PERMISSION_MANAGE_EDIT);

        for ($i = 0; $i < $limit; $i++){
        	$userFaker = \App\Models\User::create([
        		'name' => $faker->name,
				'email' => $faker->unique()->email,
				'avatar' => $faker->imageUrl($width = 150, $height = 150),
				'password' => 'secret',
				'remember_token' => \Illuminate\Support\Str::random(10),
                //{{SEEDER_NOT_DELETE_THIS_LINE}}
			]);
            $roleName = $faker->randomElement([
                LarajsPermission::ROLE_USER,
                LarajsPermission::ROLE_VISITOR,
                LarajsPermission::ROLE_CREATOR,
                LarajsPermission::ROLE_EDITOR,
            ]);
            $userFaker->syncRoles($roleName);
		}
    }
}
