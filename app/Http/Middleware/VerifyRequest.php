<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!env('MIX_HASH_KEY')) {
            return $next($request);
        }
        $methods = ['POST', 'PUT', 'PATCH'];
        $method = $request->method();
        $currentUrl = $request->path();
        $whiteList = [];
        $hashKeyClient = $request->header('Hash-Key');
        $hashKeyServer = md5(json_encode($request->all(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . env('MIX_HASH_KEY'));
        if ($hashKeyClient === $hashKeyServer || in_array($currentUrl, $whiteList) || !in_array($method, $methods)) {
            return $next($request);
        }

        return response()->json(
            [
                'success' => false,
                'message' => trans('auth.hash_key'),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
