<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Larajs\Permission as LarajsPermission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $keyword = $request->get('keyword', '');
        $permissionQuery = Permission::query();
        $permissionQuery->when($keyword, function ($q) use ($keyword) {
            return $q->where('name', 'LIKE', "%$keyword%");
        });

        return PermissionResource::collection($permissionQuery->paginate($limit));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $permission = Permission::create($request->all());
            $adminRole = Role::findByName(LarajsPermission::ROLE_ADMIN);
            $adminRole->givePermissionTo($permission);

            return $this->jsonData(new PermissionResource($permission));
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePermissionRequest $request, Permission $permission)
    {
        try {
            $permission->update($request->only(['name', 'description']));

            return $this->jsonData(new PermissionResource($permission));
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        try {
            $adminRole = Role::findByName(LarajsPermission::ROLE_ADMIN);
            $adminRole->revokePermissionTo($permission);
            $permission->delete();

            return $this->jsonSuccess(trans('messages.delete'));
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
