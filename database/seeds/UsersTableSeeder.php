<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 1000;

		\App\Models\User::create([
			'name' => 'Thanh Tan',
			'email' => 'admin@gmail.com',
			'role_id' => 1,
			'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
			'remember_token' => \Illuminate\Support\Str::random(10)
		]);

        for ($i = 0; $i < $limit; $i++){
        	\App\Models\User::create([
        		'name' => $faker->name,
				'email' => $faker->email,
				'role_id' => 2,
				'password' => \Illuminate\Support\Facades\Hash::make('secret'),
				'remember_token' => \Illuminate\Support\Str::random(10)
			]);
		}
    }
}
