<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\EloquentRepository;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
	public function getModel()
	{
		return User::class;
	}
}