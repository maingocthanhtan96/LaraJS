<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StoreAvatarRequest;
use App\Http\Requests\StoreFileRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    /**
     * upload files
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author tanmnt
     */
    public static function store(StoreFileRequest $request)
    {
        try {
            if ($request->file('file')) {
                $now = Carbon::now();
                $image = $request->file('file');
                $name =
                    time() .
                    '_' .
                    \Str::random(20) .
                    '.' .
                    $image->getClientOriginalExtension();
                $folderCreate = "/uploads/dropzone/$now->year/$now->month/$now->day";
                $folder = public_path($folderCreate);
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }
                $image->move($folder, $name);

                return (new self())->jsonData("$folderCreate/$name");
            }

            return (new self())->jsonError(trans('error.file_not_found'));
        } catch (\Exception $e) {
            return (new self())->jsonError($e->getMessage());
        }
    }

    /**
     * remove file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author
     */
    public static function remove(Request $request)
    {
        try {
            $file = $request->get('file', '');
            if ($file) {
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                } else {
                    return (new self())->jsonError(
                        trans('error.file_not_found')
                    );
                }
            }
            return (new self())->jsonSuccess(trans('messages.delete'));
        } catch (\Exception $e) {
            return (new self())->jsonError($e->getMessage());
        }
    }

    public static function storeAvatar(StoreAvatarRequest $request)
    {
        try {
            if ($request->file('file')) {
                $now = Carbon::now();
                $image = $request->file('file');
                $name =
                    time() .
                    '_' .
                    \Str::random(20) .
                    '.' .
                    $image->getClientOriginalExtension();
                $folderCreate = "/uploads/avatars/$now->year/$now->month/$now->day";
                $folder = public_path($folderCreate);
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }

                $image->move($folder, $name);

                // Remove file old
                $fileOld = $request->get('fileOld', '');
                if ($fileOld) {
                    if (file_exists(public_path($fileOld))) {
                        unlink(public_path($fileOld));
                    } else {
                        return (new self())->jsonError(
                            trans('error.file_not_found')
                        );
                    }
                }

                return (new self())->jsonData("$folderCreate/$name");
            }

            return (new self())->jsonError(trans('error.file_not_found'));
        } catch (\Exception $e) {
            return (new self())->jsonError($e->getMessage());
        }
    }
}
