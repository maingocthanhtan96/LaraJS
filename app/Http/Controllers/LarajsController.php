<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LarajsController extends Controller
{

    public function viewLarajs()
    {
        return view('larajs');
    }

    public function fallbackApi()
    {
        return response()->json([
            'message' => trans('error.404')
        ], 404);
    }
}
