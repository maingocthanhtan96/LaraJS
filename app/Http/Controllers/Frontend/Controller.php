<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $_gridPageSize;
	protected $_orderBy;
	protected $_sortType;

	public function __construct(Request $request)
	{
		$this->_gridPageSize = $request->get('grid_page_size',20);
		$this->_orderBy = $request->get('sort_field','id');
		$this->_sortType = $request->get('sort_type','desc');
	}

	/**
	 * @param     $errorMsg
	 * @param int $statusCode
	 * @param int $line
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @Author DuyLBP
	 * @Date   2018-06-27
	 */
	protected function jsonNG($errorMsg, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, $line = 0)
	{
		return response()->json(
			[
				'status'  => $statusCode,
				'message' => $errorMsg,
				'line'    => $line,
			], $statusCode
		);
	}

	/**
	 * @param     $errorMsg
	 * @param int $statusCode
	 * @param int $line
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @Author DuyLBP
	 * @Date   2018-06-27
	 */
	protected function jsonOK($data = [], $statusCode = Response::HTTP_OK)
	{
		return response()->json($data, $statusCode);

	}
}
