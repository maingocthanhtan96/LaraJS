<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonData($data = [], $status = Response::HTTP_OK)
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
    public function jsonTable($data, $status = Response::HTTP_OK)
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
     * @param string $file
     * @param string $line
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonError($message, $file = '', $line = 0, $status = Response::HTTP_INTERNAL_SERVER_ERROR)
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
    public function jsonSuccess($message, $status = Response::HTTP_OK)
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
     * @param string $message
     * @param bool $success
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public function jsonMessage(string $message, bool $success, $status = Response::HTTP_OK)
    {
        return response()->json(
            [
                'success' => $success,
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
    public function jsonString(string $string, $status = Response::HTTP_OK)
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
