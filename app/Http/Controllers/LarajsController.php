<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return response()->json([
            'message' => trans('error.404')
        ], 404);
    }
}
