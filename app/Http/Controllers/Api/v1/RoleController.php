<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $roles = Role::all();

            return RoleResource::collection($roles);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
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
    public function store(Request $request)
    {
        try {
            $role = Role::create($request->all());

            return new RoleResource($role);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
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
            $viewMenu = 'view menu ';
            $input = $request->all();
            $viewMenuPermissions = [];
            if (!$role || $role->isAdmin()) {
                return $this->jsonError('Role not found!', 404);
            }
            $permissions = $request->get('permissions', []);
            foreach ($permissions['menu'] as $menu) {
                $name = $viewMenu . $menu;
                if ($name !== $viewMenu) {
                    array_push($viewMenuPermissions, $name);
                    Permission::findOrCreate($name, 'api');
                }
            }
            $permissions = Permission::allowed()->whereIn('id', $permissions['other'])->get(['name'])->toArray();
            $permissions = array_merge($viewMenuPermissions, $permissions);
            $role->syncPermissions($permissions);
            $role->update([
                'name' => $input['role']['name'],
                'description' => $input['role']['description']
            ]);

            return new RoleResource($role);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
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
            $role = $role->delete();

            return $this->jsonData($role);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }
}
