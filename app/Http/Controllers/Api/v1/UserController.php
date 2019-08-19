<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Service\QueryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

	/** get user information
	 * @return UserResource|\Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function userInfo()
	{
		try {
			$user = \Auth::user();

			return new UserResource($user);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	/**
	 * lists
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function index(Request $request)
	{
		try {
			$limit = $request->get('limit', 25);
			$ascending = $request->get('ascending', 0);
			$orderBy = $request->get('orderBy', '');
			$query = $request->get('query', '');

			$columns = ['id' => 'id', 'email' => 'email','created_at' => 'created_at'];
			$columnsWith = [];
			$columnSearch = ['name', 'email'];
			$with = ['roles'];
			$qs = new QueryService(new User);
			$users = $qs->queryTable($columns, $columnsWith,$query, $columnSearch, $with, $limit, $ascending, $orderBy);

			return $this->jsonTable($users);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	/**
	 * create
	 * @param StoreUserRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 * @author
	 */
	public function store(StoreUserRequest $request)
	{
		try {
			$user = User::create($request->all());
			$user->assignRole($request->get('role_id'));
			return $this->jsonOk($user);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	/**
	 * get once by id
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function show(User $user)
	{
		try {
			$userShow = $user->toArray();
			$userShow['role_id'] = $user->roles->toArray()[0] ? $user->roles->toArray()[0]['id'] : '';

			return $this->jsonOk($userShow);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage(), 404);
		}
	}

	/**
	 * update once by id
	 * @param StoreUserRequest $request
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function update(StoreUserRequest $request, User $user)
	{
		try {
			$user->update($request->all());
			\DB::table('model_has_roles')->where('model_id',$user->id)->delete();
			$user->assignRole($request->get('role_id'));

			return $this->jsonOk($user);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	/**
	 * delete once by id
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
    public function destroy(User $user)
    {
	    try {
		    if($user->isAdmin()) {
			    return $this->jsonError(trans('error.is_admin'), 403);
		    }
		    $user = $user->delete();

		    return $this->jsonOk($user);
	    } catch (\Exception $e) {
	    	return $this->jsonError($e->getMessage());
	    }
    }
}
