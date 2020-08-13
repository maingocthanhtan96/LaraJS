<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LangController extends Controller
{
    public function setLanguage($language)
    {
        $week = 10080;
        $cookie = cookie('language', $language, $week);

        return response('success')->cookie($cookie);
    }
}
