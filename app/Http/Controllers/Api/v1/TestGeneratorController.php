<?php

/**
 * Created by: Tanmnt
 * Email: maingocthanhan96@gmail.com
 * Date Time: 2019-10-01 22:15:54
 * File: TestGenerator.php
 */

namespace App\Http\Controllers\Api\v1;

use App\Models\TestGenerator;
use App\Service\QueryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestGeneratorRequest;

class TestGeneratorController extends Controller
{

	/**
	 * lists
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function index(Request $request)
	{
		try {
			$limit = $request->get('limit', 25);
			$ascending = $request->get('ascending', 0);
			$orderBy = $request->get('orderBy', '');
			$query = $request->get('query', '');

			$columns = ['id', 'name_1', 'varchar', 'file_2', 'varchar'];
			$columnsWith = [];
			$columnSearch = ['name_1', 'varchar', 'file_2', 'varchar'];
			$with = [];
			$qs = new QueryService(new TestGenerator);
			$testGenerator = $qs->queryTable($columns, $columnsWith, $query, $columnSearch, $with, $limit, $ascending, $orderBy);

			return $this->jsonTable($testGenerator);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	/**
	 * create
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function store(StoreTestGeneratorRequest $request)
	{
		try {
			$testGenerator = TestGenerator::create($request->all());
			//{{CONTROLLER_RELATIONSHIP_MTM_CREATE_NOT_DELETE_THIS_LINE}}

			return $this->jsonOk($testGenerator);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	/**
	 * get once by id
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function show(TestGenerator $testGenerator)
	{
		try {
		    //{{CONTROLLER_RELATIONSHIP_MTM_SHOW_NOT_DELETE_THIS_LINE}}

			return $this->jsonOk($testGenerator);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage(), 404);
		}
	}

	/**
	 * update once by id
	 * @param Request $request
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
	public function update(StoreTestGeneratorRequest $request, TestGenerator $testGenerator)
	{
		try {
			$testGenerator->update($request->all());
            //{{CONTROLLER_RELATIONSHIP_MTM_UPDATE_NOT_DELETE_THIS_LINE}}

			return $this->jsonOk($testGenerator);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	/**
	 * delete once by id
	 * @param User $user
	 * @return \Illuminate\Http\JsonResponse
	 * @author tanmnt
	 */
    public function destroy(TestGenerator $testGenerator)
    {
	    try {
	        //{{CONTROLLER_RELATIONSHIP_MTM_DELETE_NOT_DELETE_THIS_LINE}}
			$testGenerator = $testGenerator->delete();

		    return $this->jsonOk($testGenerator);
	    } catch (\Exception $e) {
	    	return $this->jsonError($e->getMessage());
	    }
    }

    //{{CONTROLLER_RELATIONSHIP_NOT_DELETE_THIS_LINE}}
}
