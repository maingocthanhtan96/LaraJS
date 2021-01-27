<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
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
     * @param StorePermissionRequest $request
     * @return JsonResponse
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        try {
            $permission = Permission::create($request->all());

            return $this->jsonData(new PermissionResource($permission));
        } catch (\Exception $e) {
            $this->jsonError($e);
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
     * @param StorePermissionRequest $request
     * @param Permission $permission
     * @return JsonResponse
     */
    public function update(StorePermissionRequest $request, Permission $permission): JsonResponse
    {
        try {
            $permission->update($request->only(['name', 'description']));

            return $this->jsonData(new PermissionResource($permission));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return JsonResponse
     */
    public function destroy(Permission $permission): JsonResponse
    {
        try {
            $permission->delete();

            return $this->jsonMessage(trans('messages.delete'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
