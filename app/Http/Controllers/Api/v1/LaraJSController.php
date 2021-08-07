<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param $language
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function setLanguage($language)
    {
        $week = 10080;
        $cookie = cookie('language', $language, $week);

        return response('success')->cookie($cookie);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logging(Request $request): JsonResponse
    {
        try {
            $logging = $request->get('logging', 0);
            $platform = env('LOG_CHANNEL');
            switch ($logging) {
                case 0:
                    $platform = 'application';
                    break;
                case 1:
                    $platform = 'frontend';
                    break;
                case 3:
                    $platform = 'backend';
                    break;
            }
            \Log::channel($platform)->error($request->get('message'), $request->only('stack', 'info', 'screen'));

            return $this->jsonMessage('Store log success');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
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
            404
        );
    }
}
