<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function error(Request $request)
    {
        $err = $request->get('err');
        $url = $request->get('url');
        $info = $request->get('info');

        \Log::error('======ERROR-FRONTEND-ERROR======');
        $errors = 'Message: ' . $err . PHP_EOL . 'Info: ' . $info . PHP_EOL . 'Url: ' . $url;
        \Log::error($errors);

        return $this->jsonMessage(trans('messages.success'));
    }

    public function warn(Request $request)
    {
        $err = $request->get('err');
        $url = $request->get('url');
        $trace = $request->get('trace');

        \Log::warning('======ERROR-FRONTEND-WARN======');
        $errors = 'Message: ' . $err . PHP_EOL . 'Trace: ' . $trace . PHP_EOL . 'Url: ' . $url;
        \Log::warning($errors);

        return $this->jsonMessage(trans('messages.success'));
    }
}
