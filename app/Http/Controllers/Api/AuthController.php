<?php

namespace App\Http\Controllers\Api;

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
     * @return false|string
     * @author tanmnt
     */
    public function login(Request $request)
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

            $tokenRequest = $request->create(config('services.passport.login_endpoint'), 'post');
            $response = \Route::dispatch($tokenRequest);

            switch ($response->getStatusCode()) {
                case Response::HTTP_OK:
                    return $response->getContent();
                case Response::HTTP_BAD_REQUEST:
                    return $this->jsonMessage(trans('auth.login_fail'), false, $response->getStatusCode());
                case Response::HTTP_UNAUTHORIZED:
                    return $this->jsonMessage(trans('auth.credentials_incorrect'), false, $response->getStatusCode());
                default:
                    return $this->jsonMessage('Login fail', false, $response->getStatusCode());
            }
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function logout()
    {
        try {
            auth()
                ->user()
                ->tokens->each(function ($token, $key) {
                    $token->delete();
                });
            return response()->json('Logged out successfully', 200);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $this->jsonMessage(trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->jsonMessage(trans($response), false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Send password reset link.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function forgotPassword(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Handle reset password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return $this->jsonMessage(trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->jsonMessage(trans($response), false, 401);
    }
}
