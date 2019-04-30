<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Users\UserRepository;
use App\Service\QueryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends Controller
{
	protected $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function userInfo()
	{
		$user = Auth::user()->with('role')->first();

		return $this->jsonOk($user);
	}

	public function list(Request $request)
	{
		$limit = $request->get('limit', 25);
		$ascending = $request->get('ascending', 0);
		$orderBy = $request->get('orderBy', '');
		$query = $request->get('query', '');

		$columns = ['id' => 'id' ,'role.name' => 'role_id', 'email' => 'email', 'created_at' => 'created_at'];
		$columnSearch = ['name', 'email'];
		$with = ['role'];

		$qs = new QueryService(new User);
		$users = $qs->queryTable($columns, $query, $columnSearch, $with, $limit, $ascending, $orderBy);

		return $this->jsonTable($users);
	}

	public function roles()
	{
		$roles = Role::all();

		return $this->jsonOk($roles);
	}

	public function storeOrUpdate(StoreUserRequest $request, $id = null)
	{
		$user = User::updateOrCreate(
			['id' => $id],
			$request->all()
		);
		return $this->jsonOk($user);
	}

	public function edit($id)
    {
        $user = $this->userRepository->find($id);

        return $this->jsonOk($user);
    }

    public function delete($id)
    {
        $user = $this->userRepository->delete($id);

        return $this->jsonOk($user);
    }
}
