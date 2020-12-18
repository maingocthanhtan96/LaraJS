<?php

namespace App\Http\Controllers\Api;

use App\Generators\Backend\{
    ControllerGenerator,
    LangGenerator,
    MigrationGenerator,
    ModelGenerator,
    RepositoryGenerator,
    RequestGenerator,
    RouteGenerator,
    SeederGenerator,
    RelationshipGenerator,
    SwaggerGenerator,
    SwaggerRelationshipGenerator
};
use App\Generators\BackendUpdate\{
    ControllerUpdateGenerator,
    LangUpdateGenerator,
    MigrationUpdateGenerator,
    ModelUpdateGenerator,
    SeederUpdateGenerator,
    RequestUpdateGenerator,
    SwaggerUpdateGenerator
};
use App\Generators\Frontend\{ApiGenerator, FormGenerator, FormHandlerGenerator, ViewGenerator, RouteGenerator as RouteGeneratorFe, ViewTableGenerator};
use App\Generators\FrontendUpdate\{FormUpdateGenerator, ViewTableUpdateGenerator, ViewUpdateGenerator};
use App\Http\Requests\StoreGeneratorRelationshipRequest;
use App\Services\{FileService, GeneratorService, QueryService};
use App\Models\Generator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GeneratorController extends Controller
{
    /*@var service*/
    private $serviceGenerator;

    public function __construct()
    {
        $this->serviceGenerator = new GeneratorService();
    }

    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 25);
            $ascending = $request->get('ascending', '0');
            $orderBy = $request->get('orderBy', '');
            $search = $request->get('search', '');
            $betweenDate = $request->get('created_at', []);

            $queryService = new QueryService(new Generator());
            $queryService->order = ['id', 'created_at'];
            $queryService->columnSearch = ['table'];
            $queryService->withRelationship = [];
            $queryService->search = $search;
            $queryService->betweenDate = $betweenDate;
            $queryService->limit = $limit;
            $queryService->ascending = $ascending;
            $queryService->orderBy = $orderBy;

            $query = $queryService->queryTable();
            $query = $query->paginate($limit);
            $generators = $query->toArray();

            return $this->jsonTable($generators);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function show(Generator $generator)
    {
        try {
            return $this->jsonData($generator);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $fields = $request->get('fields', []);
            $model = $request->get('model', []);
            // git commit
            //            $this->_gitCommit($model['name']);
            $generateBackend = $this->_generateBackend($fields, $model);
            $this->_generateFrontend($fields, $model);
            $files = $this->_generateFile($model, $generateBackend);

            Generator::create([
                'field' => json_encode($fields),
                'model' => json_encode($model),
                'table' => $this->serviceGenerator->tableName($model['name']),
                'files' => json_encode($files),
            ]);
            $this->_runCommand($model);

            return $this->jsonMessage(trans('messages.success'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
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
                'dropFields' => $dropFields,
            ];
            // git commit
            $this->_gitCommit($model['name']);
            $this->_generateBackendUpdate($generator, $model, $updateFields);
            $this->_generateFrontendUpdate($generator, $model, $updateFields);
            $generator->update([
                'field' => json_encode($fields),
            ]);
            $this->_runCommand();

            return $this->jsonMessage(trans('messages.success'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    /**
     * @param Request $request
     * @param Generator $generator
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Generator $generator): \Illuminate\Http\JsonResponse
    {
        try {
            $model = json_decode($generator->model, true);
            $files = json_decode($generator->files, true);
            $generatorService = new GeneratorService();
            $fileService = new FileService();

            //            $this->_gitCommit($model['name']);
            // START - Remove File
            //            if (file_exists($files['migration'])) {
            //                $fileMigration = str_replace(base_path(), '', $files['migration']);
            //                $fileMigration = ltrim($fileMigration, '/');
            //                Artisan::call("migrate:rollback --path={$fileMigration}");
            //            }
            //            foreach ($files as $key => $file) {
            //                if (is_array($file)) {
            //                    foreach ($file as $keyInside => $fileInside) {
            //                        if (file_exists($file[$keyInside])) {
            //                            unlink($file[$keyInside]);
            //                        }
            //                    }
            //                } else {
            //                    if (file_exists($files[$key])) {
            //                        unlink($files[$key]);
            //                    }
            //                }
            //            }
            // END - Remove File

            // START - search route
            $templateDataRouteReal = $this->serviceGenerator->getFile('api_routes', 'laravel');
            $startRoute = "/*<==> {$model['name']} Route -";
            $endRoute = "'{$model['name']}Controller');";
            $templateDataRoute = $generatorService->searchTemplate($startRoute, $endRoute, -strlen($startRoute) + 22, strlen($endRoute) + 8, $templateDataRouteReal);
            dd($templateDataRoute);
            $templateDataRouteReal = str_replace($templateDataRoute, '', $templateDataRouteReal);
            $fileService->createFileReal(config('generator.path.laravel.api_routes'), $templateDataRouteReal);
            // END - search route
            // START - search lang
            $nameLanguages = ['route', 'table'];
            $languages = config('generator.not_delete.laravel.lang');
            $tableName = $this->serviceGenerator->tableNameNotPlural($model['name']);
            foreach ($languages as $key => $langComment) {
                foreach ($nameLanguages as $nameLang) {
                    $templateDataLangReal = $this->serviceGenerator->getFile('lang', 'laravel', $key . '/' . $nameLang . '.php');
                    if ($nameLang === 'route') {
                        $startRouteTable = "// START - {$generatorService->tableNameNotPlural($model['name'])}";
                        $endRouteTable = "// END - {$generatorService->tableNameNotPlural($model['name'])}";
                        $templateDataLangRoute = $generatorService->searchTemplate($startRouteTable, $endRouteTable, -strlen($startRouteTable) + 22, strlen($endRouteTable) + 8, $templateDataLangReal);
                        $templateDataLangReal = str_replace($templateDataLangRoute, '', $templateDataLangReal);
                    }

                    if ($nameLang === 'table') {
                        $quoteTable = "'" . $tableName . "' => [";
                        $templateDataLangTable = $this->serviceGenerator->searchTemplate($quoteTable, '],', -strlen($quoteTable) + 20, strlen($quoteTable) - 16, $templateDataLangReal);
                        $templateDataLangReal = str_replace($templateDataLangTable, '', $templateDataLangReal);
                    }
                    $fileService->createFileReal(config('generator.path.laravel.lang') . $key . '/' . $nameLang . '.php', $templateDataLangReal);
                }
            }
            // END - search lang

            // START - api route VueJS
            $pathApiRouteVueJSReal = config('generator.path.vuejs.router') . 'index.js';
            $templateDataApiRouteVueJSReal = $this->serviceGenerator->getFile('router', 'vuejs', 'index.js');
            $templateDataApiRouteVueJSReal = str_replace(
                "import {$generatorService->modelNameNotPluralFe($model['name'])} from './modules/{$generatorService->nameAttribute($model['name'])}';\n",
                '',
                $templateDataApiRouteVueJSReal
            );
            $templateDataApiRouteVueJSReal = str_replace("{$generatorService->modelNameNotPluralFe($model['name'])},\n", '', $templateDataApiRouteVueJSReal);
            $fileService->createFileReal($pathApiRouteVueJSReal, $templateDataApiRouteVueJSReal);
            // END - api route VueJS

            //            $generator->delete();

            return $this->jsonMessage(trans('messages.success'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function checkModel(Request $request)
    {
        $serviceGenerator = new GeneratorService();
        $name = $request->get('name', '');
        try {
            if ($name) {
                $name = $serviceGenerator->tableName($name);
                if (Schema::hasTable($name)) {
                    //table exist
                    return $this->jsonMessage(1);
                }
                // table not exist
                return $this->jsonMessage(2);
            } else {
                //name null
                return $this->jsonMessage(3);
            }
        } catch (\Exception $e) {
            return $this->jsonError($e);
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
            // git commit
            $this->_gitCommit($model);
            new RelationshipGenerator($relationship, $model, $modelCurrent, $column, $column2, $options);
            new SwaggerRelationshipGenerator($relationship, $model, $modelCurrent);
            $this->_runCommand();

            return $this->jsonMessage(trans('messages.success'));
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function generateDiagram(Request $request)
    {
        try {
            $model = $request->get('model');
            $diagram = $this->serviceGenerator->getRelations($model);

            return $this->jsonData($diagram);
        } catch (\Exception $e) {
            return $this->jsonError($e);
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
                if ($modelName !== $modelTable) {
                    if (!in_array($modelName, $ignoreModel)) {
                        $modelData[] = $modelName;
                    }
                }
            }
            return $this->jsonData($modelData);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function getColumns(Request $request)
    {
        try {
            $table = $request->get('table');
            $table = \Str::snake(\Str::plural($table));
            $columns = \Schema::getColumnListing($table);

            return $this->jsonData($columns);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    private function _generateBackend($fields, $model)
    {
        $migrationGenerator = new MigrationGenerator($fields, $model);
        new ControllerGenerator($fields, $model);
        new SeederGenerator($fields, $model);
        new ModelGenerator($fields, $model);
        new RepositoryGenerator($fields, $model);
        new LangGenerator($fields, $model);
        new RouteGenerator($model);
        new RequestGenerator($fields, $model);
        new SwaggerGenerator($fields, $model);

        return [
            'migration' => [
                'file' => $migrationGenerator->file,
            ],
        ];
    }

    private function _generateFrontend($fields, $model)
    {
        new RouteGeneratorFe($model);
        new ApiGenerator($model);
        new ViewTableGenerator($fields, $model);
        new FormGenerator($fields, $model);
        new FormHandlerGenerator($fields, $model);
    }

    private function _generateFile($model, $generateBackend): array
    {
        $files = [];
        $configGeneratorLaravel = config('generator')['path']['laravel'];
        $configGeneratorVueJS = config('generator')['path']['vuejs'];

        $files['api_controller'] = $configGeneratorLaravel['api_controller'] . $model['name'] . 'Controller.php';
        $files['request'] = $configGeneratorLaravel['request'] . 'Store' . $model['name'] . 'Request.php';
        $files['swagger'] = $configGeneratorLaravel['swagger'] . $model['name'] . '.php';
        $files['model'] = $configGeneratorLaravel['model'] . $model['name'] . '.php';
        $files['repositories']['interface'] = $configGeneratorLaravel['repositories'] . $model['name'] . '/' . $model['name'] . 'Interface.php';
        $files['repositories']['repository'] = $configGeneratorLaravel['repositories'] . $model['name'] . '/' . $model['name'] . 'Repository.php';
        $files['migration'] = $configGeneratorLaravel['migration'] . $generateBackend['migration']['file'];
        $files['seeder'] = $configGeneratorLaravel['seeder'] . $model['name'] . 'TableSeeder.php';

        $files['api'] = $configGeneratorVueJS['api'] . $model['name'] . '.js';
        $files['router_modules'] = $configGeneratorVueJS['api'] . $model['name'] . '.js';
        $files['views']['form'] = $configGeneratorVueJS['views'] . lcfirst(\Str::kebab($model['name'])) . '/' . 'Form.vue';
        $files['views']['index'] = $configGeneratorVueJS['views'] . lcfirst(\Str::kebab($model['name'])) . '/' . 'index.vue';

        return $files;
    }

    private function _generateBackendUpdate($generator, $model, $updateFields)
    {
        new MigrationUpdateGenerator($generator, $model, $updateFields);
        new ModelUpdateGenerator($model, $updateFields);
        new SeederUpdateGenerator($generator, $model, $updateFields);
        new ControllerUpdateGenerator($model, $updateFields);
        new LangUpdateGenerator($model, $updateFields);
        new RequestUpdateGenerator($generator, $model, $updateFields);
        new SwaggerUpdateGenerator($generator, $model, $updateFields);
    }

    private function _generateFrontendUpdate($generator, $model, $updateFields)
    {
        new ViewTableUpdateGenerator($generator, $model, $updateFields);
        new FormUpdateGenerator($generator, $model, $updateFields);
    }

    private function _runCommand($model = [])
    {
        if (isset($model['options'])) {
            if (!$this->serviceGenerator->getOptions(config('generator.model.options.ignore_migrate'), $model['options'])) {
                Artisan::call('migrate --force');
            }
        } else {
            Artisan::call('migrate --force');
        }
        $resourcePath = resource_path('js/assets/images/diagram-erd.png');
        $basePath = base_path();
        Artisan::call('vue-i18n:generate');
        // php artisan generate:erd /Applications/MAMP/htdocs/tanmnt/larajs/resources/js/assets/images/diagram-erd.png
        //        exec("cd $basePath && php artisan generate:erd $resourcePath");
        exec("cd $basePath && ./swagger.sh");
        //        $this->_gitResetHEAD();
    }

    private function _gitCommit($model)
    {
        $basePath = base_path();
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $commit = '"' . $model . ' - ' . $now . '"';

        $gitAdd = new Process(['git', 'add', '.'], $basePath);
        $gitAdd->run();
        if (!$gitAdd->isSuccessful()) {
            throw new ProcessFailedException($gitAdd);
        }

        $gitCommit = new Process(['git', 'commit', "-m $commit"], $basePath);
        $gitCommit->run();
        if (!$gitCommit->isSuccessful()) {
            throw new ProcessFailedException($gitCommit);
        }
    }

    private function _gitResetHEAD()
    {
        $basePath = base_path();
        $gitResetHEAD = new Process(['git', 'reset', 'HEAD^'], $basePath);

        $gitResetHEAD->run();
        if (!$gitResetHEAD->isSuccessful()) {
            throw new ProcessFailedException($gitResetHEAD);
        }
    }
}
