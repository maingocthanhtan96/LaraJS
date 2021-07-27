<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $user = $this->route('user', null);
        $id = $user ? $user->id : null;
        return [
            'name' => 'required',
            'email' => "required|string|email|max:255|unique:users,email,$id,id,deleted_at,NULL",
            'avatar' => 'required',
            'password' => [
                $id ? '' : 'required',
                'confirmed',
                'min:8',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[a-z]/', $value)) {
                        return $fail(trans('validation.password.lowercase', ['number' => 1]));
                    }
                    if (!preg_match('/[A-Z]/', $value)) {
                        return $fail(trans('validation.password.uppercase', ['number' => 1]));
                    }
                    if (!preg_match('/[0-9]/', $value)) {
                        return $fail(trans('validation.password.number', ['number' => 1]));
                    }
                    if (!preg_match('/[@$!%*#?&]/', $value)) {
                        return $fail(trans('validation.password.symbols', ['number' => 1]));
                    }
                },
            ],

            //{{REQUEST_RULES_NOT_DELETE_THIS_LINE}}
        ];
    }
}
