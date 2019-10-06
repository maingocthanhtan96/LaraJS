<?php

namespace App\Http\Controllers\Api\v1;

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
	public function store(Request $request)
	{
		if($request->file('file'))
		{
			$now = Carbon::now();
			$image = $request->file('file');
			$name = time() . '_'. \Str::random(20).'.'.$image->getClientOriginalExtension();
			$folderCreate = "/uploads/dropzone/$now->year/$now->month/$now->day";
			$folder = public_path($folderCreate);
			if(!is_dir($folder)) {
				mkdir($folder, 0775, true);
			}
			$image->move($folder, $name);
		}

		return $this->jsonOk("$folderCreate/$name");
	}

	/**
	 * remove files
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function remove(Request $request)
	{
        $file = $request->get('file', '');
        if($file) {
            if(file_exists(public_path($file))){
                unlink(public_path($file));
            } else {
                return $this->jsonError(trans('error.file_not_found'));
            }
        }

		return $this->jsonSuccess(trans('messages.delete'));
	}
}
