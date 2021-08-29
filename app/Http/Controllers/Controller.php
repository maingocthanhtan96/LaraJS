<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array|object $data
     * @param int $status
     * @return JsonResponse
     * @author tanmnt
     */
    public function jsonData($data = [], int $status = Response::HTTP_OK): JsonResponse
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
    public function jsonTable($data, int $status = Response::HTTP_OK): JsonResponse
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
    public function jsonError($error, int $status = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $message = $error;
        $file = '';
        $line = '';
        if (is_object($error)) {
            write_log_exception($error);
            $message = $error->getMessage();
            $file = env('APP_ENV') === 'production' ? '' : $error->getFile();
            $line = env('APP_ENV') === 'production' ? '' : $error->getLine();
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
    public function jsonMessage($message, bool $success = true, int $status = Response::HTTP_OK): JsonResponse
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
    public function jsonValidate($errors, bool $success = false, int $status = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json(
            [
                'success' => $success,
                'errors' => $errors,
            ],
            $status
        );
    }
}
