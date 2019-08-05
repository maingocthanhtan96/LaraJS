<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
	{
		$http = new \GuzzleHttp\Client;
		try {
			$response = $http->post(config('services.passport.login_endpoint'), [
				'form_params' => [
					'grant_type' => 'password',
					'client_id' => config('services.passport.client_id'),
					'client_secret' => config('services.passport.client_secret'),
					'username' => $request->email,
					'password' => $request->password,
				]
			]);
			return $response->getBody();
		} catch (\GuzzleHttp\Exception\BadResponseException $e) {
			if ($e->getCode() === 400) {
				return $this->jsonError(trans('auth.login_fail'), $e->getCode());
			} else if ($e->getCode() === 401) {
				return $this->jsonError(trans('auth.credentials_incorrect'), $e->getCode());
			}
			return $this->jsonError(trans('auth.server_error'), $e->getCode());
		}
	}

	public function register(StoreUserRequest $request)
	{
		return User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);
	}

	public function logout()
	{
		auth()->user()->tokens->each(function ($token, $key) {
			$token->delete();
		});
		return response()->json('Logged out successfully', 200);
	}
}
