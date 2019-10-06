<?php

use Illuminate\Database\Seeder;

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

		\App\Models\User::create([
			'name' => 'Thanh Tan',
			'email' => 'admin@larajs.com',
			'password' => 'admin123',
			'remember_token' => \Illuminate\Support\Str::random(10)
		]);
		\App\Models\User::create([
			'name' => 'Thanh Tan',
			'email' => 'user@larajs.com',
			'password' => 'user123',
			'remember_token' => \Illuminate\Support\Str::random(10)
		]);

        for ($i = 0; $i < $limit; $i++){
        	\App\Models\User::create([
        		'name' => $faker->name,
				'email' => $faker->email,
				'password' => 'secret',
				'remember_token' => \Illuminate\Support\Str::random(10),
                //{{SEEDER_NOT_DELETE_THIS_LINE}}
			]);
		}
    }
}
