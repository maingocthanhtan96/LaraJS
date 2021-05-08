<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     * @author tanmnt
     */
    public function jsonData($data = [], $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data,
            ],
            $status
        );
    }

    /**
     * @param $data
     * @param int $status
     * @return JsonResponse
     * @author tanmnt
     */
    public function jsonTable($data, $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data['data'],
                'count' => $data['total'],
            ],
            $status
        );
    }

    /**
     * @param $error
     * @param int $status
     * @return JsonResponse
     * @author tanmnt
     */
    public function jsonError($error, $status = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $message = $error;
        $file = '';
        $line = '';
        if (is_object($error)) {
            write_log_exception($error);
            $message = $error->getMessage();
            $file = $error->getFile();
            $line = $error->getLine();
        }

        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'file' => $file,
                'line' => $line,
            ],
            $status
        );
    }

    /**
     * @param $message
     * @param bool $success
     * @param int $status
     *
     * @return JsonResponse
     * @author tanmnt
     */
    public function jsonMessage($message, bool $success = true, $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            [
                'success' => $success,
                'message' => $message,
            ],
            $status
        );
    }

    /**
     * @param $errors
     * @param bool $success
     * @param int $status
     * @return JsonResponse
     */
    public function jsonValidate($errors, bool $success = false, $status = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'errors' => $errors
        ], $status);
    }
}
