<?php

namespace App\Http\Controllers\Api\v1;

use App\Generators\Backend\{ControllerGenerator, LangGenerator, MigrationGenerator, ModelGenerator,RepositoryGenerator, RouteGenerator, SeederGenerator, RelationshipGenerator};
use App\Generators\BackendUpdate\{ControllerUpdateGenerator,
	LangUpdateGenerator,
	MigrationUpdateGenerator,
	ModelUpdateGenerator,
	SeederUpdateGenerator};
use App\Generators\Frontend\{ApiGenerator, FormGenerator, FormHandlerGenerator, PageGenerator, RouteGenerator as RouteGeneratorFe};
use App\Generators\FrontendUpdate\{FormUpdateGenerator, PageUpdateGenerator};
use App\Http\Requests\StoreGeneratorRelationshipRequest;
use App\Service\{GeneratorService, QueryService};
use App\Models\Generator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class GeneratorController extends Controller
{
	/*@var service*/
	private $serviceGenerator;

	public function __construct()
	{
		$this->serviceGenerator = new GeneratorService;
	}

	public function index(Request $request)
	{
		try {
			$limit = $request->get('limit', 25);
			$ascending = $request->get('ascending', 0);
			$orderBy = $request->get('orderBy', '');
			$query = $request->get('query', '');

			$columns = ['id' => 'id', 'created_at' => 'created_at'];
			$columnsWith = [];
			$columnSearch = ['table'];
			$with = [];
			$qs = new QueryService(new Generator());
			$generator = $qs->queryTable($columns, $columnsWith, $query, $columnSearch, $with, $limit, $ascending, $orderBy);

			return $this->jsonTable($generator);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	public function show(Generator $generator)
    {
		try {
			return $this->jsonOk($generator);
		} catch (\Exception $e) {
			return $this->jsonError($e->getMessage(), 404);
		}
	}

	public function store(Request $request)
    {
	    try {
            $fields = $request->get('fields', []);
            $model = $request->get('model', []);
            $this->_generateBackend($fields, $model);
            $this->_generateFrontend($fields, $model);
            Generator::create([
		    	'field' => json_encode($fields),
			    'model' => json_encode($model),
			    'table' => $this->serviceGenerator->tableName($model['name']),
		    ]);
            $this->_runCommand();
            return $this->jsonSuccess(trans('messages.success'), 204);
        } catch (\Exception $e) {
	    	return $this->jsonError($e->getMessage());
	    }
    }

    public function update(Request $request, Generator $generator)
    {
	    try {
            $fields = $request->get('fields', []);
            $updateFields = $request->get('fields_update', []);
            $model = $request->get('model', []);
		    $renameFields = $request->get('rename', []);
		    $changeFields = $request->get('change', []);
		    $dropFields = $request->get('drop', []);
		    $updateFields = [
			    'updateFields' => $updateFields,
			    'renameFields' => $renameFields,
			    'changeFields' => $changeFields,
			    'dropFields' => $dropFields
		    ];
            $this->_generateBackendUpdate($generator, $model, $updateFields);
            $this->_generateFrontendUpdate($generator, $model, $updateFields);
            $generator->update([
                'field' => json_encode($fields),
            ]);
            $this->_runCommand();
            return $this->jsonSuccess(trans('messages.success'), 204);
	    } catch (\Exception $e) {
	    	return $this->jsonError($e->getMessage());
	    }
    }

    public function checkModel(Request $request)
    {
    	$serviceGenerator = new GeneratorService();
    	$name = $request->get('name', '');
	    if($name) {
		    $name = $serviceGenerator->tableName($name);
		    if (Schema::hasTable($name))
		    {
		    	//table exist
			    return $this->jsonSuccess(1);
		    }
		    // table not exist
		    return $this->jsonSuccess(2);
	    } else {
	    	//name null
		    return $this->jsonSuccess(3);
	    }
    }

    public function generateRelationship(StoreGeneratorRelationshipRequest $request)
    {
        try {
            $relationship = $request->get('relationship');
            $model = $request->get('model');
            $modelCurrent = $request->get('model_current');
            $column = $request->get('column');
            $column2 = $request->get('column2');
            $options = $request->get('options', []);
            new RelationshipGenerator($relationship, $model, $modelCurrent, $column, $column2, $options);
            $this->_runCommand();
            return $this->jsonSuccess(trans('messages.success'), 204);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }

    public function generateDiagram(Request $request)
    {
        try {
            $model = $request->get('model');
            $diagram = $this->serviceGenerator->getRelations($model);

            return $this->jsonOk($diagram);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }

    public function getModels(Request $request)
    {
        try {
            $table = $request->get('model', []);
            $table = json_decode($table, true);
            $modelTable = $table['name'];
            $pathModel = config('generator.path.laravel.model');
            $ignoreModel = config('generator.relationship.ignore_model');
            $models = \File::files($pathModel);
            $modelData = [];
            foreach ($models as $model) {
                $modelName = $model->getBasename('.php');
                if($modelName !== $modelTable) {
                    if(!in_array($modelName, $ignoreModel)) {
                        $modelData[] = $modelName;
                    }
                }
            }
            return $this->jsonOk($modelData);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }

    public function getColumns(Request $request)
    {
        try {
            $table = $request->get('table');
            $table = \Str::snake(\Str::plural($table));
            $columns = \Schema::getColumnListing($table);

            return $this->jsonOk($columns);
        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }

    private function _generateBackend($fields, $model)
	{
	    new MigrationGenerator($fields, $model);
		new ControllerGenerator($fields, $model);
		new SeederGenerator($fields, $model);
		new ModelGenerator($fields, $model);
		new RepositoryGenerator($fields, $model);
		new LangGenerator($fields, $model);
		new RouteGenerator($model);
	}

	private function _generateFrontend($fields, $model)
	{
		new RouteGeneratorFe($model);
		new ApiGenerator($model);
		new PageGenerator($fields, $model);
		new FormGenerator($fields, $model);
		new FormHandlerGenerator($fields, $model);
	}

	private function _generateBackendUpdate($generator, $model, $updateFields)
	{
		new MigrationUpdateGenerator($generator, $model, $updateFields);
		new ModelUpdateGenerator($model, $updateFields);
		new SeederUpdateGenerator($generator, $model, $updateFields);
		new ControllerUpdateGenerator($model, $updateFields);
		new LangUpdateGenerator($model, $updateFields);
	}

	private function _generateFrontendUpdate($generator, $model, $updateFields)
	{
		new PageUpdateGenerator($model, $updateFields);
		new FormUpdateGenerator($generator, $model, $updateFields);
	}

	private function _runCommand()
	{
		Artisan::call('migrate');
		Artisan::call('vue-i18n:generate');
		Artisan::call('generate:erd resources/js/icons/svg/diagram-erd.svg --format=svg');
		exec('npm run dev');
	}
}
