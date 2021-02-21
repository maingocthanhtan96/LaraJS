<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        return view('frontend.application');
    }
}
