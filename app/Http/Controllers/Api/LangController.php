<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LangController extends Controller
{
    public function setLanguage($language)
    {
        \Session::put('language', $language);
        return $language;
    }
}
