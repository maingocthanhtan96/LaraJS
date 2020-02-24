<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $limit = 25;

    /**
     * @param array $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonData($data = [], $status = 200)
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data,
            ],
            $status,
        );
    }

    /**
     * @param $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonTable($data, $status = 200)
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data['data'],
                'count' => $data['total'],
            ],
            $status,
        );
    }

    /**
     * @param $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonError($message, $file = '', $line = '', $status = 500)
    {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'file' => $file,
                'line' => $line,
            ],
            $status,
        );
    }

    /**
     * @param $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonSuccess($message, $status = 200)
    {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
            ],
            $status,
        );
    }

    /**
     * @param string $string
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonString(string $string, $status = 200)
    {
        return response()->json(
            [
                'success' => true,
                'name' => $string,
            ],
            $status,
        );
    }
}
