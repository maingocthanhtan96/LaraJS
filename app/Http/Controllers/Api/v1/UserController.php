<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\QueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * UserController constructor.
     * @author tanmnt
     */
    function __construct()
    {
        $this->middleware('permission:' . \ACL::PERMISSION_VISIT, ['only' => ['index']]);
        $this->middleware('permission:' . \ACL::PERMISSION_CREATE, ['only' => ['store']]);
        $this->middleware('permission:' . \ACL::PERMISSION_EDIT, ['only' => ['show', 'update']]);
        $this->middleware('permission:' . \ACL::PERMISSION_DELETE, ['only' => ['destroy']]);
    }

    /** get user information
     * @return UserResource|JsonResponse
     * @author tanmnt
     */
    public function userInfo()
    {
        try {
            $user = \Auth::user();

            return $this->jsonData(new UserResource($user));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * lists
     * @param Request $request
     * @return JsonResponse
     * @author tanmnt
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 25);
            $ascending = $request->get('ascending', 0);
            $orderBy = $request->get('orderBy', '');
            $search = $request->get('search', '');
            $betweenDate = $request->get('updated_at', []);

            $queryService = new QueryService(new User());
            $queryService->select = [];
            $queryService->order = ['id', 'updated_at'];
            $queryService->columnSearch = ['name', 'email'];
            $queryService->withRelationship = ['roles'];
            $queryService->search = $search;
            $queryService->betweenDate = $betweenDate;
            $queryService->ascending = $ascending;
            $queryService->orderBy = $orderBy;

            $query = $queryService->queryTable();
            $query = $query->paginate($limit);
            $users = $query->toArray();

            return $this->jsonTable($users);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * create
     * @param StoreUserRequest $request
     * @return JsonResponse
     * @author
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->all());
            $user->assignRole($request->get('role_id'));
            //{{CONTROLLER_RELATIONSHIP_MTM_CREATE_NOT_DELETE_THIS_LINE}}

            return $this->jsonData($user, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * get once by id
     * @param User $user
     * @return JsonResponse
     * @author tanmnt
     */
    public function show(User $user): JsonResponse
    {
        try {
            $user['role_id'] = $user->roles->toArray()[0] ? $user->roles->toArray()[0]['id'] : '';
            $user = $user->toArray();
            //{{CONTROLLER_RELATIONSHIP_MTM_SHOW_NOT_DELETE_THIS_LINE}}

            return $this->jsonData($user);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * update once by id
     * @param StoreUserRequest $request
     * @param User $user
     * @return JsonResponse
     * @author tanmnt
     */
    public function update(StoreUserRequest $request, User $user): JsonResponse
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
            return $this->jsonError($e);
        }
    }

    /**
     * delete once by id
     * @param User $user
     * @return JsonResponse
     * @author tanmnt
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            if ($user->isAdmin()) {
                return $this->jsonMessage(trans('error.is_admin'), false, 403);
            }
            //{{CONTROLLER_RELATIONSHIP_MTM_DELETE_NOT_DELETE_THIS_LINE}}
            $user->delete();

            return $this->jsonMessage(trans('messages.delete'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    //{{CONTROLLER_RELATIONSHIP_NOT_DELETE_THIS_LINE}}
}
