<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    const VIEW_MENU = 'view menu ';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $roles = Role::all();

            return $this->jsonData(RoleResource::collection($roles));
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $role = Role::create($request->all());

            return $this->jsonData(new RoleResource($role));
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
    public function update(Request $request, Role $role)
    {
        try {
            if (!$role || $role->isAdmin()) {
                return $this->jsonError('Role not found!', 404);
            }
            $viewMenu = self::VIEW_MENU;
            $input = $request->all();
            $viewMenuPermissions = [];
            $permissions = $request->get('permissions', []);
            foreach ($permissions['menu'] as $menu) {
                $name = $viewMenu . $menu;
                if ($name !== $viewMenu) {
                    array_push($viewMenuPermissions, $name);
                    Permission::findOrCreate($name, 'api');
                }
            }
            if (\Auth::user()->isPermission()) {
                $permissions = Permission::whereIn('id', $permissions['other'])
                    ->get(['name'])
                    ->toArray();
            } else {
                $permissions = Permission::allowed()
                    ->whereIn('id', $permissions['other'])
                    ->get(['name'])
                    ->toArray();
            }
            $permissions = array_merge($viewMenuPermissions, $permissions);
            $role->syncPermissions($permissions);
            $role->update([
                'name' => $input['role']['name'],
                'description' => $input['role']['description'],
            ]);

            return $this->jsonData(new RoleResource($role));
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
    public function destroy(Role $role)
    {
        try {
            if (!$role || $role->isAdmin()) {
                return $this->jsonError('Role not found!', 404);
            }
            $role->delete();

            return $this->jsonSuccess(trans('messages.delete'));
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
