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

	public function jsonOk($data = [])
	{
		return response()->json([
			'success' => true,
			'data' => $data
		], 200);
	}

	public function jsonTable($data)
	{
		return response()->json([
			'data' => $data['data'],
			'count' => ($data['total'])
		]);
	}

	public function jsonError($message)
	{
		return response()->json([
			'success' => false,
			'message' => $message
		], 500);
	}
}
