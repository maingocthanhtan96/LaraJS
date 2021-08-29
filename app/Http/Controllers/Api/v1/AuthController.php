<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\MailResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @author tanmnt
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $email = $request->get('email');
            $password = $request->get('password');
            $request->request->add([
                'username' => $email,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
            ]);

            return $this->_getToken($request);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function feLogin(Request $request): JsonResponse
    {
        try {
            $email = $request->get('email');
            $password = $request->get('password');
            $request->request->add([
                'username' => $email,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_fe_id'),
                'client_secret' => config('services.passport.client_fe_secret'),
            ]);

            return $this->_getToken($request);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $request->request->add([
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->get('refresh_token'),
                'client_id' => config('services.passport.client_fe_id'),
                'client_secret' => config('services.passport.client_fe_secret'),
            ]);

            return $this->_getToken($request);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @return JsonResponse
     * @author tanmnt
     */
    public function logout(): JsonResponse
    {
        try {
            \Auth::user()
                ->token()
                ->delete();

            return $this->jsonMessage(trans('auth.logout'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @return JsonResponse
     */
    public function feLogout(): JsonResponse
    {
        try {
            \Auth::user()
                ->token()
                ->delete();

            return $this->jsonMessage(trans('auth.logout'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * Send password reset link.
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $email = $request->get('email');
            $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->jsonError(trans('passwords.user'));
            }
            $passwordReset = PasswordReset::firstOrNew(['email' => $email]);
            $passwordReset->email = $email;
            $passwordReset->token = \Str::random(60);
            $passwordReset->created_at = now();
            $passwordReset->save();
            $user->notify(new MailResetPasswordNotification($passwordReset->token));

            return $this->jsonMessage(trans('passwords.sent'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        try {
            $passwordReset = PasswordReset::where('token', $request->get('token'))->first();
            if (
                Carbon::parse($passwordReset->created_at)
                    ->addMinutes(PasswordReset::EXPIRE_TOKEN)
                    ->isPast() ||
                !$passwordReset
            ) {
                return $this->jsonMessage(trans('passwords.token'), false);
            }
            $user = User::where('email', $passwordReset->email)->firstOrFail();
            $user->password = $request->get('password');
            $user->save();
            PasswordReset::where('token', $request->get('token'))->delete();

            return $this->jsonMessage(trans('passwords.reset'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    private function _getToken($request): JsonResponse
    {
        $tokenRequest = $request->create(config('services.passport.login_endpoint'), 'post');
        $response = \Route::dispatch($tokenRequest);
        switch ($response->getStatusCode()) {
            case Response::HTTP_OK:
                return $this->jsonData(json_decode($response->getContent(), true));
            case Response::HTTP_BAD_REQUEST:
                return $this->jsonMessage(trans('auth.login_fail'), false, $response->getStatusCode());
            case Response::HTTP_UNAUTHORIZED:
                return $this->jsonMessage(trans('auth.credentials_incorrect'), false, $response->getStatusCode());
            default:
                return $this->jsonMessage($response->getContent(), false, $response->getStatusCode());
        }
    }
}
