<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $permission = $this->route()->parameter('permission');
        if ($permission) {
            $permission = $permission->id;
        }
        return [
            'name' => "required|string|unique:permissions,name,{$permission},id",
        ];
    }
}
