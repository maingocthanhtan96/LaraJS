<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    public const AUTH_ERROR = 'authentication_error';

    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards) === self::AUTH_ERROR) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Unauthorized',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }

    protected function authenticate($request, array $guards)
    {
        if (!$guards) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        return self::AUTH_ERROR;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
