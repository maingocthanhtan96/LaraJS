<?php

namespace App\Http\Controllers;

class LaraJSController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory\Illuminate\View\View
     * @author tanmnt
     */
    public function __invoke()
    {
        return view('larajs');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function fallbackApi()
    {
        return response()->json(
            [
                'message' => trans('error.404'),
            ],
            404,
        );
    }
}
