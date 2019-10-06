<?php

use Illuminate\Database\Seeder;

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
				'name' => 'admin',
                'description' => 'Super Administrator. Have access and full permission to all pages.'
			]
		);
		\App\Models\Role::create(
			[
				'name' => 'user',
                'description' => 'Normal user. Have access to some pages',
			]
		);
        \App\Models\Role::create(
            [
                'name' => 'visitor',
                'description' => 'Just a visitor. Can only see the home page and the document page',
            ]
        );
        \App\Models\Role::create(
            [
                'name' => 'editor',
                'description' => 'Just a editor. Can only edit page',
            ]
        );
        \App\Models\Role::create(
            [
                'name' => 'manager',
                'description' => 'Just a manager. Can you manage',
            ]
        );
    }
}
