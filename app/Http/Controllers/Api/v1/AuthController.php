<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

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
            auth()
                ->user()
                ->tokens->each(function ($token) {
                    $token->delete();
                });

            return $this->jsonMessage('Logged out successfully');
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
            \Auth::user()->token()->delete();

            return $this->jsonMessage('Logged out successfully');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logging(Request $request): JsonResponse
    {
        try {
            $logging = $request->get('logging', 0);
            $platform = env('LOG_CHANNEL');
            switch ($logging) {
                case 0:
                    $platform = 'application';
                    break;
                case 1:
                    $platform = 'frontend';
                    break;
                case 3:
                    $platform = 'backend';
                    break;
            }
            \Log::channel($platform)->error($request->get('message'), $request->only('stack', 'info', 'screen'));

            return $this->jsonMessage('Store log success');
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
                return $this->jsonMessage('Login fail', false, $response->getStatusCode());
        }
    }


    /**
     * Get the response for a successful password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, string $response)
    {
        return $this->jsonMessage(trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, string $response)
    {
        return $this->jsonMessage(trans($response), false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Send password reset link.
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function forgotPassword(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Handle reset password
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword(CanResetPassword $user, string $password)
    {
        $user->password = $password;
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetResponse(Request $request, string $response)
    {
        return $this->jsonMessage(trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, string $response)
    {
        return $this->jsonMessage(trans($response), false, 401);
    }
}
