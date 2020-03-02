<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Service\QueryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * UserController constructor.
     * @author tanmnt
     */
    function __construct()
    {
        $this->middleware('permission:visit', ['only' => ['index']]);
        $this->middleware('permission:create', ['only' => ['store']]);
        $this->middleware('permission:edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:delete', ['only' => ['destroy']]);
    }

    /** get user information
     * @return UserResource|\Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function userInfo()
    {
        try {
            $user = \Auth::user();

            return $this->jsonData(new UserResource($user));
        } catch (\Exception $e) {
            writeLogException($e);
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
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
            $search = $request->get('search', '');
            $betweenDate = $request->get('created_at', []);

            $queryService = new QueryService(new User());
            $queryService->order = ['id', 'created_at'];
            $queryService->orderRelationship = [];
            $queryService->columnSearch = ['name', 'email'];
            $queryService->withRelationship = ['roles'];
            $queryService->search = $search;
            $queryService->betweenDate = $betweenDate;
            $queryService->limit = $limit;
            $queryService->ascending = $ascending;
            $queryService->orderBy = $orderBy;

            return $this->jsonTable($queryService->queryTable());
        } catch (\Exception $e) {
            writeLogException($e);
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
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
            //{{CONTROLLER_RELATIONSHIP_MTM_CREATE_NOT_DELETE_THIS_LINE}}

            return $this->jsonData($user, 201);
        } catch (\Exception $e) {
            writeLogException($e);
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
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
            $user['role_id'] = $user->roles->toArray()[0] ? $user->roles->toArray()[0]['id'] : '';
            $user = $user->toArray();
            //{{CONTROLLER_RELATIONSHIP_MTM_SHOW_NOT_DELETE_THIS_LINE}}

            return $this->jsonData($user);
        } catch (\Exception $e) {
            writeLogException($e);
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
            \DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->delete();
            $user->assignRole($request->get('role_id'));
            //{{CONTROLLER_RELATIONSHIP_MTM_UPDATE_NOT_DELETE_THIS_LINE}}

            return $this->jsonData($user);
        } catch (\Exception $e) {
            writeLogException($e);
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
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
            if ($user->isAdmin()) {
                return $this->jsonError(trans('error.is_admin'), 403);
            }
            //{{CONTROLLER_RELATIONSHIP_MTM_DELETE_NOT_DELETE_THIS_LINE}}
            $user->delete();

            return $this->jsonSuccess(trans('messages.delete'), 204);
        } catch (\Exception $e) {
            writeLogException($e);
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    //{{CONTROLLER_RELATIONSHIP_NOT_DELETE_THIS_LINE}}
}
