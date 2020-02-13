<?php

namespace App\Http\Controllers;

class LarajsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory\Illuminate\View\View
     * @author tanmnt
     */
    public function viewLarajs()
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
                'message' => trans('error.404')
            ],
            404
        );
    }
}
