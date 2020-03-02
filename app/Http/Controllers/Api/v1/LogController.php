<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function error(Request $request)
    {
        $err = $request->get('err');
        $url = $request->get('url');
        $info = $request->get('info');

        \Log::error(PHP_EOL);
        \Log::error('Message: ' . $err);
        \Log::error('Info: ' . $info);
        \Log::error('Url: ' . $url);

        return $this->jsonSuccess(trans('messages.success'));
    }

    public function warn(Request $request)
    {
        $err = $request->get('err');
        $url = $request->get('url');
        $trace = $request->get('trace');

        \Log::warning(PHP_EOL);
        \Log::warning('Message: ' . $err);
        \Log::warning('Trace: ' . $trace);
        \Log::warning('Url: ' . $url);

        return $this->jsonSuccess(trans('messages.success'));
    }
}
