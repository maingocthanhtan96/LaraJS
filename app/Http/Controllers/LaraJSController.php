<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LaraJSController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function __invoke()
    {
        return view('LaraJS');
    }

    /**
     * @return JsonResponse
     * @author tanmnt
     */
    public function fallbackApi(): JsonResponse
    {
        return response()->json(
            [
                'message' => trans('error.404'),
            ],
            404,
        );
    }
}
