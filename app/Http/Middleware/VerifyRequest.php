<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        $methods = ['POST', 'PUT', 'PATCH'];
        $method = $request->method();
        $currentUrl = $request->path();
        $whiteList = [];
        $hashKeyClient = $request->header('Hash-Key');
        $hashKeyServer = md5(json_encode($request->all()) . env('MIX_HASH_KEY'));
        if ($hashKeyClient === $hashKeyServer || in_array($currentUrl, $whiteList) || !in_array($method, $methods) || env('APP_DEBUG')) {
            return $next($request);
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'Hash key not match.',
            ],
            \Illuminate\Http\Response::HTTP_BAD_REQUEST,
        );
    }
}
