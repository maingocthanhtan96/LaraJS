<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class RelationshipGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    /** @var string */
    public $notDelete;

    /** @var string */
    public $relationship;

    CONST REF_UPPER = 'Ref';
    CONST _REF_LOWER = 'ref_';

    public function __construct($relationship, $model, $modelCurrent, $column, $column2, $options)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.migration');
        $this->notDelete = config('generator.not_delete.laravel.model');
        $this->relationship = config('generator.relationship.relationship');

        $this->generate($relationship, $model, $modelCurrent, $column, $column2, $options);
    }

    private function generate($relationship, $model, $modelCurrent, $column, $column2, $options)
    {
        $pathTemplate = 'Models/';
        $template = $this->serviceGenerator->get_template("relationship", $pathTemplate);
        // Model Relationship
        if ($relationship === $this->relationship['has_one'] || $relationship === $this->relationship['has_many']) {
            $templateModel = str_replace('{{FUNCTION_NAME}}', \Str::camel($model), $template);
            $templateInverse = str_replace('{{FUNCTION_NAME}}', \Str::camel($modelCurrent), $template);
        } else {
            $templateModel = str_replace('{{FUNCTION_NAME}}', $this->serviceGenerator->modelNamePluralFe($model), $template);
            $templateInverse = str_replace('{{FUNCTION_NAME}}', $this->serviceGenerator->modelNamePluralFe($modelCurrent), $template);
        }
        $templateModel = str_replace('{{RELATION}}', $relationship, $templateModel);
        $templateModel = str_replace('{{RELATION_MODEL_CLASS}}', $model, $templateModel);
        //ModelCurrent Relationship

        $templateInverse = str_replace('{{RELATION_MODEL_CLASS}}', $modelCurrent, $templateInverse);
        if ($relationship === $this->relationship['belongs_to_many']) {
            $templateInverse = str_replace('{{RELATION}}', 'belongsToMany', $templateInverse);
            $templateModel = str_replace('{{FIELD_RELATIONSHIP}}', "'" . self::_REF_LOWER . \Str::snake($model) . '_' . \Str::snake($modelCurrent) . "', " . "'" . \Str::snake($modelCurrent) . "_id', " . "'" . \Str::snake($model) . "_id'", $templateModel);
            $templateModel = str_replace(", 'id'", "", $templateModel);
            $templateInverse = str_replace('{{FIELD_RELATIONSHIP}}', "'" . self::_REF_LOWER . \Str::snake($model) . '_' . \Str::snake($modelCurrent) . "', " . "'" . \Str::snake($model) . "_id', " . "'" . \Str::snake($modelCurrent) . "_id'", $templateInverse);
            $templateInverse = str_replace(", 'id'", "", $templateInverse);
        } else {
            $templateModel = str_replace('{{FIELD_RELATIONSHIP}}', "'" . \Str::snake($modelCurrent) . '_id' . "'", $templateModel);
            $templateInverse = str_replace('{{FIELD_RELATIONSHIP}}', "'" . \Str::snake($modelCurrent) . '_id' . "'", $templateInverse);
            $templateInverse = str_replace('{{RELATION}}', 'belongsTo', $templateInverse);
        }
        $this->_migrateRelationship($relationship, $model, $modelCurrent, $column, $column2, $options);
        //replace file model real
        $templateModelReal = $this->serviceGenerator->getFile('model', 'laravel', $model . '.php');
        $this->_replaceFile($model, $templateInverse, $templateModelReal);
        //replace file model current real
        $templateModelCurrentReal = $this->serviceGenerator->getFile('model', 'laravel', $modelCurrent . '.php');
        $this->_replaceFile($modelCurrent, $templateModel, $templateModelCurrentReal);
    }

    private function _migrateRelationship($relationship, $model, $modelCurrent, $column, $column2, $options)
    {
        $now = Carbon::now();
        $pathTemplate = 'Databases/Migrations/';
        $templateData = $this->serviceGenerator->get_template("migrationRelationship", $pathTemplate);
        $templateData = str_replace('{{DATE_TIME}}', $now->toDateTimeString(), $templateData);
        if ($relationship === $this->relationship['belongs_to_many']) {
            //belongsToMany
            $templateData = $this->serviceGenerator->get_template("migrationRelationshipMTM", $pathTemplate);
            //if belongsToMany replace table to create
            $templateData = $this->_replaceTemplateRelationshipMTM($model, $modelCurrent, $templateData);
            $fileName = date('Y_m_d_His') . '_' . 'create_' . \Str::snake($model) . '_' . \Str::snake($modelCurrent) . '_table.php';
            $this->_generateModelMTM($model, $modelCurrent);
            $this->_generateSeederMTM($model, $modelCurrent);
            $this->_generateRoute($modelCurrent);
            $this->_generateRoute($model);
            $this->_generateController($modelCurrent, $model, $options, $column, $relationship);
            $this->_generateController($model, $modelCurrent, $options, $column2, $relationship);
            //generate frontend
            $this->_generateIndexFe($model, $column, $options, \Str::snake($modelCurrent), $relationship);
            $this->_generateIndexFe($modelCurrent, $column2, $options, \Str::snake($model), $relationship);
            $this->_generateFormFe($modelCurrent, $model, $column, $relationship);
            $this->_generateFormFe($model, $modelCurrent, $column2, $relationship);
        } else {
            //hasOne or hasMany
            $templateData = $this->_replaceTemplateRelationship($model, $modelCurrent, $templateData);
            $fileName = date('Y_m_d_His') . '_' . 'create_' . $this->serviceGenerator->tableName($model) . '_table.php';
            $this->_generateModel($modelCurrent, $model);
            $this->_generateSeeder($modelCurrent);
            $this->_generateRoute($modelCurrent);
            $this->_generateController($modelCurrent, $model, $options, $column, $relationship);
            //generate frontend
            $this->_generateIndexFe($model, $column, $options, \Str::snake($modelCurrent), $relationship);
            $this->_generateFormFe($modelCurrent, $model, $column, $relationship);
        }

        $this->serviceFile->createFile($this->path, $fileName, $templateData);
    }

    private function _generateFormFe($model, $modelRelationship, $columnRelationship, $relationship)
    {
        $notDelete = config('generator.not_delete.vuejs.form');
        $fileName = $this->serviceGenerator->modelNameNotPluralFe($modelRelationship) . '/form.vue';
        $templateDataReal = $this->serviceGenerator->getFile('views', 'vuejs', $fileName);
        $dataForm = 'form:';
        //create form
        $templateDataForm = $this->serviceGenerator->searchTemplateX($dataForm, 1, '},', strlen($dataForm) - 1, -strlen($dataForm) + 1, $templateDataReal);
        $dataForms = explode(',', trim($templateDataForm));
        $fieldsGenerateDataForm = [];
        foreach ($dataForms as $form) {
            if (strlen($form) > 0) {
                $form = trim($form);
                list($keyForm, $valForm) = explode(':', $form, 2);
                $name = $keyForm . ':' . $valForm . ',';
                $fieldsGenerateDataForm[] = $name;
            }
        }
        $columnDidGenerate = \Str::snake($model) . '_id' . ": '',";
        $fieldsGenerateDataForm[] = $columnDidGenerate;
        $templateDataReal = str_replace("$dataForm {" . $this->serviceGenerator->infy_nl_tab(1, 0) . $templateDataForm . "},", "$dataForm {" . $this->replaceTemplate($fieldsGenerateDataForm, 2, 3, 2) . "},", $templateDataReal);
        //create form item
        $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['item'], $this->_generateSelect(\Str::snake($model), \Str::snake($model) . '_id', $columnRelationship, $relationship), 3, $templateDataReal, 4);

        //create rules
        if ($relationship !== $this->relationship['belongs_to_many']) {
            $templateRules = $this->getHandlerTemplate('rules');
            $templateRules = str_replace('{{$FIELD$}}', \Str::snake($model) . '_id', $templateRules);
            $templateRules = str_replace('{{$ATTRIBUTE_FIELD$}}', 'this.$t(\'route.' . \Str::snake($modelRelationship) . '\')', $templateRules);
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['rules'], $templateRules, 2, $templateDataReal);
        }
        //generate api
        $this->_generateApi($model);
        //add generate api
        $templateDataReal = $this->_generateAddApi($model, $modelRelationship, $templateDataReal, $notDelete);
        $fileName = config('generator.path.vuejs.views') . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
        return $templateDataReal;
    }

    private function _generateAddApi($model, $modelRelationship, $templateDataReal, $notDelete)
    {
        $mountedStub = 'mounted() {';
        $stubGetData = $this->serviceGenerator->get_template("getDataRelationship", 'Handler/', 'vuejs');
        $stubGetData = str_replace('{{$MODEL$}}', \Str::camel($model), $stubGetData);
        $stubGetData = str_replace('{{$MODEL_RELATIONSHIP$}}', \Str::camel($model), $stubGetData);
        $stubGetData = str_replace('{{$MODEL_UPPERCASE$}}', ucfirst(\Str::camel($model)), $stubGetData);
        $templateDataReal = str_replace($mountedStub, $mountedStub . $this->serviceGenerator->infy_nl_tab(1, 2, 2) . $stubGetData, $templateDataReal);
        $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['data'], \Str::camel($model) . 'List: [],', 3, $templateDataReal, 2);
        $importStub = "import " . $this->serviceGenerator->modelNameNotPlural($model) . "Resource" . " from '@/api/" . \Str::camel($model) . "';";
        if (!stripos($templateDataReal, $importStub)) {
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['import_component'], $importStub, 0, $templateDataReal, 2);
            //relationship
            $stubRelationship = "const " . \Str::camel($model) . "Resource" . " = new " . $this->serviceGenerator->modelNameNotPlural($model) . "Resource();";
            // current
            $stubRCurrent = "const " . \Str::camel($modelRelationship) . "Resource" . " = new " . $this->serviceGenerator->modelNameNotPlural($modelRelationship) . "Resource();";
            $templateDataReal = str_replace($stubRCurrent, $stubRCurrent . $this->serviceGenerator->infy_nl_tab(1, 0) . $stubRelationship, $templateDataReal);
        }
        return $templateDataReal;
    }

    private function _generateApi($model)
    {
        $checkFuncName = 'get' . $model;
        $notDelete = config('generator.not_delete.vuejs.form');
        $fileName = $this->serviceGenerator->modelNameNotPluralFe($model) . '.js';
        $templateDataReal = $this->serviceGenerator->getFile('api', 'vuejs', $fileName);
        if (strpos($templateDataReal, $checkFuncName)) {
            return $templateDataReal;
        }
        $stubAPI = $this->serviceGenerator->get_template("apiRelationship", 'Api/', 'vuejs');
        $templateAPI = str_replace('{{$FUNCTION$}}', $model, $stubAPI);
        $templateAPI = str_replace('{{$MODEL$}}', $this->serviceGenerator->urlResource($model), $templateAPI);
        $templateAPI = str_replace('{{$MODEL_RELATIONSHIP$}}', $this->serviceGenerator->urlResource($model), $templateAPI);
        $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['api'], $templateAPI, 1, $templateDataReal, 2);
        //check import
        $importStub = "import request from '@/utils/request';";
        $resourceStub = "import Resource from '@/api/resource';";
        $checkImport = strpos($templateDataReal, $importStub);
        if (!$checkImport) {
            $templateDataReal = str_replace($resourceStub, $resourceStub . $this->serviceGenerator->infy_nl_tab(1, 0) . $importStub, $templateDataReal);
        }

        $this->serviceFile->createFile(config('generator.path.vuejs.api'), $fileName, $templateDataReal);
        return $templateDataReal;
    }

    private function _generateIndexFe($modelRelationship, $columnRelationship, $options, $funcName, $relationship)
    {
        $createdAt = 'created_at';
        $actions = 'actions';
        $configOptions = config('generator.relationship.options');
        $notDelete = config('generator.not_delete.vuejs.views');
        $fileName = $this->serviceGenerator->modelNameNotPluralFe($modelRelationship) . '/index.vue';
        $templateDataReal = $this->serviceGenerator->getFile('views', 'vuejs', $fileName);

        if (in_array($configOptions['show'], $options)) {
            //columns
            $fieldsGenerateColumns = [];
            $columns = 'columns: [';
            $templateColumns = $this->serviceGenerator->searchTemplateX($columns, 3, '],', 0, 0, $templateDataReal);
            $arColumns = explode(',', trim($templateColumns));
            foreach ($arColumns as $col) {
                if (strlen($col) > 0) {
                    $col = trim($col);
                    $col = trim($col, "''");
                    $name = "'" . $col . "'";
                    if ($name !== "'" . $createdAt . "'" && $name !== "'" . $actions . "'") {
                        $fieldsGenerateColumns[] = $name;
                    }
                }
            }
            $nameRelationship = $funcName . '.' . $columnRelationship;
            $fieldsGenerateColumns[] = "'" . $nameRelationship . "'";
            $fieldsGenerateColumns[] = "'" . $createdAt . "'";
            $fieldsGenerateColumns[] = "'" . $actions . "'";

            $templateDataReal = str_replace("$columns" . $templateColumns . "]", "$columns" . implode(', ', $fieldsGenerateColumns) . "]", $templateDataReal);
            //headings
            $fieldsGenerateHeadings = [];
            $headings = 'headings: {';
            $templateHeadings = $this->serviceGenerator->searchTemplateX($headings, 3, '},', 0, 0, $templateDataReal);
            $arHeadings = explode(',', trim($templateHeadings));
            $createHeading = "'" . $createdAt . "': () => " . 'this.$t(\'date.created_at\'),';
            foreach ($arHeadings as $heading) {
                if (strlen($heading) > 0) {
                    $heading = trim($heading);
                    list($keyHeading, $valHeading) = explode('() =>', $heading);
                    $keyHeading = trim($keyHeading);
                    $valHeading = trim($valHeading);
                    $keyHeading = trim($keyHeading, "'':");
                    $name = "'" . $keyHeading . "'" . ': () => ' . $valHeading . ',';
                    if ($name !== $createHeading) {
                        $fieldsGenerateHeadings[] = $name;
                    }
                }
            }
            $fieldsGenerateHeadings[] = "'" . $nameRelationship . "'" . ': () => this.$t(\'route.' . $funcName . '\')' . ',';
            $fieldsGenerateHeadings[] = $createHeading;


            if ($relationship === $this->relationship['belongs_to_many']) {
                //replace template datatable
                $pathTemplate = 'Handler/';
                $templateTag = $this->serviceGenerator->get_template("tagRelationshipMTM", $pathTemplate, 'vuejs');
                $fileNameTag = $funcName . '.' . $columnRelationship;
                $templateTag = str_replace('{{$FIELD_NAME$}}', $fileNameTag, $templateTag);
                $templateTag = str_replace('{{$MODEL_RELATIONSHIP$}}', $this->serviceGenerator->tableName($funcName), $templateTag);
                $templateTag = str_replace('{{$COLUMN_DISPLAY$}}', $columnRelationship, $templateTag);
                $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['templates'], $templateTag, 5, $templateDataReal, 2);
            }

            $templateDataReal = str_replace("$headings" . $templateHeadings
                . "},", "$headings" . $this->replaceTemplateHeading($fieldsGenerateHeadings, 3) . "},", $templateDataReal);
            //sort
            if (in_array($configOptions['sort'], $options)) {
                $fieldsGenerateSort = [];
                $sortable = 'sortable: [';
                $templateSortable = $this->serviceGenerator->searchTemplateX($sortable, 3, '],', 0, 0, $templateDataReal);
                $arSorts = explode(',', trim($templateSortable));
                foreach ($arSorts as $sort) {
                    if (strlen($sort) > 0) {
                        $sort = trim($sort);
                        $sort = trim($sort, "''");
                        $name = "'" . $sort . "'";
                        if ($name !== "'" . $createdAt . "'") {
                            $fieldsGenerateSort[] = $name;
                        }
                    }
                }
                $nameRelationship = $funcName . '.' . $columnRelationship;
                $fieldsGenerateSort[] = "'" . $nameRelationship . "'";
                $fieldsGenerateSort[] = "'" . $createdAt . "'";
                $templateDataReal = str_replace("$sortable" . $templateSortable . "]", "$sortable" . implode(', ', $fieldsGenerateSort) . "]", $templateDataReal);
            }
        }
        $fileName = config('generator.path.vuejs.views') . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
    }

    private function _generateOptions($modelRelationship, $options, $templateDataReal, $columnRelationship, $relationship)
    {
        $configOptions = config('generator.relationship.options');
        foreach ($options as $option) {
            if ($option === $configOptions['sort']) {
                $columns = '$columns = [';
                $columnWith = '$columnsWith = [';
                $templateColumns = $this->serviceGenerator->searchTemplate($columns, '];', 0, 0, $templateDataReal);
                $templateColumnWith = $this->serviceGenerator->searchTemplate($columnWith, '];', 0, 0, $templateDataReal);
                $commaColumns = ',';
                if (\Str::endsWith($templateColumns, ',') || strlen($templateColumns) === strlen($columns)) {
                    $commaColumns = '';
                }
                $columnDidGenerate = '"' . \Str::snake($modelRelationship) . '_id' . '"';
                $templateDataReal = str_replace("$templateColumns]", "$templateColumns" . "$commaColumns" . "$columnDidGenerate]", $templateDataReal);
                if ($relationship === $this->relationship['has_one']) {
                    $funcRelationship = \Str::snake($modelRelationship) . '.' . $columnRelationship;
                } else {
                    $funcRelationship = $this->serviceGenerator->modelNamePluralFe($modelRelationship) . '.' . $columnRelationship;
                }
                $templateDataReal = str_replace("$templateColumns]", "$templateColumns" . "$commaColumns" . "$columnDidGenerate]", $templateDataReal);
                //columnWith

                $sortRelationship = '"' . $funcRelationship . '"' . " => " . $columnDidGenerate . ',';
                if (strlen($templateColumnWith) === strlen($columnWith)) {
                    $templateDataReal = str_replace("$templateColumnWith]", "$templateColumnWith" . $this->serviceGenerator->infy_nl_tab(1, 4) . $sortRelationship . $this->serviceGenerator->infy_nl_tab(1, 3) . "]", $templateDataReal);
                } else {
                    $templateDataReal = str_replace("$templateColumnWith]", "$templateColumnWith" . $this->serviceGenerator->infy_nl_tab(0, 1) . $sortRelationship . $this->serviceGenerator->infy_nl_tab(1, 3) . "]", $templateDataReal);
                }
            } else if ($option === $configOptions['show']) {
                $columnsWith = '$with = [';
                $templateColumnWith = $this->serviceGenerator->searchTemplate($columnsWith, '];', 0, 0, $templateDataReal);
                $commaColumnWith = ',';
                if (\Str::endsWith($templateColumnWith, ',') || strlen($templateColumnWith) === strlen($columnsWith)) {
                    $commaColumnWith = '';
                }
                if ($relationship === $this->relationship['has_one'] || $relationship === $this->relationship['has_many']) {
                    $withRelationship = '"' . $this->serviceGenerator->modelNameNotPluralFe($modelRelationship) . '"';
                } else {
                    $withRelationship = "'" . $this->serviceGenerator->modelNamePluralFe($modelRelationship) . "'";
                }

                $templateDataReal = str_replace("$templateColumnWith]", "$templateColumnWith" . "$commaColumnWith" . "$withRelationship]", $templateDataReal);
            } else if ($option === $configOptions['search']) {
                $columnSearch = '$columnSearch = [';
                $templateColumnSearch = $this->serviceGenerator->searchTemplate($columnSearch, '];', 0, 0, $templateDataReal);
                $commaSearch = ',';
                if (\Str::endsWith($templateColumnSearch, ',') || strlen($templateColumnSearch) === strlen($columnSearch)) {
                    $commaSearch = '';
                }
                if ($relationship === $this->relationship['belongs_to_many']) {
                    $columnDidGenerate = '"' . \Str::camel($modelRelationship) . '.' . $columnRelationship . '"';
                } else {
                    $columnDidGenerate = '"' . \Str::snake($modelRelationship) . '_id' . '"';
                }
                $templateDataReal = str_replace("$templateColumnSearch]", "$templateColumnSearch" . "$commaSearch" . "$columnDidGenerate]", $templateDataReal);
            }
        }
        return $templateDataReal;
    }

    private function _generateController($modelRelationship, $model, $options, $column, $relationship)
    {
        $notDelete = config('generator.not_delete.laravel.controller');
        $pathTemplate = 'Controllers/';
        $fileName = $model . 'Controller.php';
        $templateDataReal = $this->serviceGenerator->getFile('api_controller', 'laravel', $fileName);
        //if belongs_to_many
        if ($relationship === $this->relationship['belongs_to_many']) {
            $templateCreateUpdate = $this->serviceGenerator->get_template("createUpdateRelationship", $pathTemplate);
            //replace create or update
            $paramCreateUpdateStub = $this->serviceGenerator->modelNameNotPluralFe($modelRelationship) . 'Id';
            $templateCreateUpdate = str_replace('{{FIELD_MODEL_ID}}', $paramCreateUpdateStub, $templateCreateUpdate);
            $templateCreateUpdate = str_replace('{{SNAKE_FIELD_MODEL_ID}}', $this->serviceGenerator->tableNameNotPlural($modelRelationship) . '_id', $templateCreateUpdate);
            $templateCreateUpdate = str_replace('{{SNAKE_FIELD_MODEL_ID}}', $this->serviceGenerator->tableNameNotPlural($modelRelationship) . '_id', $templateCreateUpdate);
            $templateCreateUpdate = str_replace('{{MODEL}}', $this->serviceGenerator->modelNameNotPluralFe($model), $templateCreateUpdate);
            $templateCreateUpdate = str_replace('{{MODEL_RELATIONSHIP}}', $this->serviceGenerator->modelNamePluralFe($modelRelationship), $templateCreateUpdate);
            $templateCreate = str_replace('{{ATTACH_ASYNC}}', 'attach', $templateCreateUpdate);
            $templateUpdate = str_replace('{{ATTACH_ASYNC}}', 'sync', $templateCreateUpdate);
            //replace create
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['relationship_mtm_create'], $templateCreate, 3, $templateDataReal);
            //replace update
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['relationship_mtm_update'], $templateUpdate, 3, $templateDataReal);
            //replace show
            $templateShow = $this->serviceGenerator->get_template("showRelationship", $pathTemplate);
            $templateShow = str_replace('{{MODEL}}', $this->serviceGenerator->modelNameNotPluralFe($model), $templateShow);
            $templateShow = str_replace('{{SNAKE_MODEL_RELATIONSHIP_ID}}', $this->serviceGenerator->tableNameNotPlural($modelRelationship) . '_id', $templateShow);
            $templateShow = str_replace('{{MODEL_RELATIONSHIP}}', $this->serviceGenerator->modelNamePluralFe($modelRelationship), $templateShow);
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['relationship_mtm_show'], $templateShow, 3, $templateDataReal);
            //replace delete
            $templateDelete = $this->serviceGenerator->get_template("deleteRelationship", $pathTemplate);
            $templateDelete = str_replace('{{MODEL}}', $this->serviceGenerator->modelNameNotPluralFe($model), $templateDelete);
            $templateDelete = str_replace('{{MODEL_RELATIONSHIP}}', $this->serviceGenerator->modelNamePluralFe($modelRelationship), $templateDelete);
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['relationship_mtm_delete'], $templateDelete, 3, $templateDataReal);
        }
        //generate options
        $templateDataReal = $this->_generateOptions($modelRelationship, $options, $templateDataReal, $column, $relationship);
        $path = config('generator.path.laravel.api_controller');
        $fileName = $path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
        //generate controller
        $fileNameFunc = $modelRelationship . 'Controller.php';
        $templateDataRealFunc = $this->serviceGenerator->getFile('api_controller', 'laravel', $fileNameFunc);
        if (!stripos($templateDataRealFunc, 'get' . $modelRelationship)) {
            $templateDataFunc = $this->serviceGenerator->get_template("relationship", $pathTemplate);
            $templateDataFunc = str_replace('{{MODEL_RELATIONSHIP}}', $modelRelationship, $templateDataFunc);
            $templateDataFunc = str_replace('{{PARAM_MODEL_RELATIONSHIP}}', $this->serviceGenerator->modelNamePluralFe($modelRelationship), $templateDataFunc);
            $templateDataRealFunc = $this->serviceGenerator->replaceNotDelete($notDelete['relationship'], $templateDataFunc, 1, $templateDataRealFunc);
            $fileNameFunc = $path . $fileNameFunc;
            $this->serviceFile->createFileReal($fileNameFunc, $templateDataRealFunc);
        }
    }

    private function _generateRoute($modelRelationship)
    {
        $templateDataReal = $this->serviceGenerator->getFile('api_routes', 'laravel');
        if (!stripos($templateDataReal, 'get' . $modelRelationship)) {
            $stubResource = "Route::apiResource('{{RESOURCE}}', '{{MODEL_CLASS}}Controller');";
            $stubRoute = "Route::get('/{{MODEL}}/get-{{MODEL_RELATIONSHIP}}', '{{CONTROLLER}}Controller@get{{ACTION}}');";
            $templateResource = str_replace('{{RESOURCE}}', $this->serviceGenerator->urlResource($modelRelationship), $stubResource);
            $templateResource = str_replace('{{MODEL_CLASS}}', $modelRelationship, $templateResource);
            $templateRoute = str_replace('{{MODEL}}', $this->serviceGenerator->urlResource($modelRelationship), $stubRoute);
            $templateRoute = str_replace('{{MODEL_RELATIONSHIP}}', $this->serviceGenerator->urlResource($modelRelationship), $templateRoute);
            $templateRoute = str_replace('{{CONTROLLER}}', $modelRelationship, $templateRoute);
            $templateRoute = str_replace('{{ACTION}}', $modelRelationship, $templateRoute);
            $templateDataReal = str_replace($templateResource, $templateRoute . $this->serviceGenerator->infy_nl_tab(1, 3) . $templateResource, $templateDataReal);
            $path = config('generator.path.laravel.api_routes');
            $this->serviceFile->createFileReal($path, $templateDataReal);

        }
    }

    private function _replaceFile($model, $templateModel, $templateReal)
    {
        $templateReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['relationship'], $templateModel, 1, $templateReal);
        $path = config('generator.path.laravel.model') . $model . '.php';
        $this->serviceFile->createFileReal($path, $templateReal);
    }

    private function _replaceTemplateRelationship($model, $modelDif, $templateData)
    {
        $templateData = str_replace('{{TABLE_NAME_TITLE}}', \Str::plural($model), $templateData);
        $templateData = str_replace('{{TABLE_NAME}}', $this->serviceGenerator->tableName($model), $templateData);
        $templateData = str_replace('{{FOREIGN_KEY}}', \Str::snake($modelDif) . '_id', $templateData);
        $templateData = str_replace('{{TABLE_FOREIGN_KEY}}', $this->serviceGenerator->tableName($modelDif), $templateData);

        return $templateData;
    }

    private function _replaceTemplateRelationshipMTM($model, $modelCurrent, $templateData)
    {
        $now = Carbon::now();
        $templateData = str_replace('{{DATE_TIME}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{TABLE_NAME_TITLE}}', self::REF_UPPER . $model . $modelCurrent, $templateData);
        $templateData = str_replace('{{TABLE_NAME}}', self::_REF_LOWER . \Str::snake($model) . '_' . \Str::snake($modelCurrent), $templateData);
        $templateData = str_replace('{{FOREIGN_KEY_1}}', \Str::snake($model) . '_id', $templateData);
        $templateData = str_replace('{{FOREIGN_KEY_2}}', \Str::snake($modelCurrent) . '_id', $templateData);
        $templateData = str_replace('{{TABLE_FOREIGN_KEY_1}}', $this->serviceGenerator->tableName($model), $templateData);
        $templateData = str_replace('{{TABLE_FOREIGN_KEY_2}}', $this->serviceGenerator->tableName($modelCurrent), $templateData);

        return $templateData;
    }

    private function _generateModel($model, $modelRelationship)
    {
        $field = \Str::snake($model) . '_id';
        $fieldsGenerate = [];
        $fieldAble = 'protected $fillable = [';
        $templateDataReal = $this->serviceGenerator->getFile('model', 'laravel', $modelRelationship . '.php');
        $template = $this->serviceGenerator->searchTemplate($fieldAble, '];', strlen($fieldAble), -strlen($fieldAble), $templateDataReal);
        $arTemplate = explode(',', trim($template));
        foreach ($arTemplate as $tpl) {
            if (strlen($tpl) > 0) {
                $fieldsGenerate[] = $tpl . ',';
            }
        }
        $fieldsGenerate[] = "'" . $field . "'" . ',';
        $implodeString = implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
        $templateDataReal = str_replace($template, $this->serviceGenerator->infy_nl_tab(1, 2) . $implodeString . $this->serviceGenerator->infy_nl_tab(1, 1), $templateDataReal);
        $this->_createFileAll('model', $modelRelationship, $templateDataReal);
        return $templateDataReal;
    }

    private function _generateModelMTM($model, $modelCurrent)
    {
        $fieldModel = \Str::snake($model) . '_id';
        $fieldModelCurrent = \Str::snake($modelCurrent) . '_id';
        $now = Carbon::now();
        $pathTemplate = 'Models/';
        $templateData = $this->serviceGenerator->get_template("model", $pathTemplate);
        $templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{MODEL_CLASS}}', self::REF_UPPER . $model . $modelCurrent, $templateData);
        $arFields = ["'" . $fieldModel . "',", "'" . $fieldModelCurrent . "',"];
        $implodeFields = implode($this->serviceGenerator->infy_nl_tab(1, 2), $arFields);
        $templateData = str_replace('{{FIELDS}}', $implodeFields, $templateData);
        $templateData = str_replace('{{TABLE_NAME}}', self::_REF_LOWER . \Str::snake($model) . '_' . \Str::snake($modelCurrent), $templateData);
        $templateData = str_replace('{{CATS}}', '', $templateData);
        $fileName = self::REF_UPPER . $model . $modelCurrent . '.php';
        $path = config('generator.path.laravel.model');
        $this->serviceFile->createFile($path, $fileName, $templateData);

        return $templateData;
    }

    private function _generateSeeder($model)
    {
        $field = \Str::snake($model) . '_id';
        $notDelete = config('generator.not_delete.laravel.db');
        $fileName = $model . 'TableSeeder.php';
        $templateDataReal = $this->serviceGenerator->getFile('seeder', 'laravel', $fileName);
        $fakerCreate = '$faker = Faker\Factory::create();';
        $param = '$' . \Str::camel(\Str::plural($model));
        $fieldRelationship = $param . " = \App\Models\\" . $model . "::all()->pluck('id')->toArray();";
        $templateDataReal = str_replace($fakerCreate, $fakerCreate . $this->serviceGenerator->infy_nl_tab(1, 2) . $fieldRelationship, $templateDataReal);
        $templateDataReal = $this->serviceGenerator->replaceNotDelete($notDelete['seeder'], "'" . $field . "' => " . '$faker->randomElement(' . $param . '),', 4, $templateDataReal);
        $this->_createFileAll('seeder', $model . 'TableSeeder', $templateDataReal);
        return $templateDataReal;
    }

    private function _generateSeederMTM($model, $modelCurrent)
    {
        $now = Carbon::now();
        $notDelete = config('generator.not_delete.laravel.db');
        $fieldModel = \Str::snake($model) . '_id';
        $fieldModelCurrent = \Str::snake($modelCurrent) . '_id';
        $fileName = self::REF_UPPER . $model . $modelCurrent . 'TableSeeder.php';
        $pathTemplate = 'Databases/Seeds/';
        $templateData = $this->serviceGenerator->get_template("seeder", $pathTemplate);
        $fakerCreate = '$faker = Faker\Factory::create();';
        $paramModel = '$' . \Str::camel(\Str::plural($model));
        $paramModelCurrent = '$' . \Str::camel(\Str::plural($modelCurrent));
        $fieldRelationshipModel = $paramModel . " = \App\Models\\" . $model . "::all()->pluck('id')->toArray();";
        $fieldRelationshipModelCurrent = $paramModelCurrent . " = \App\Models\\" . $modelCurrent . "::all()->pluck('id')->toArray();";
        $templateData = str_replace($fakerCreate, $fakerCreate . $this->serviceGenerator->infy_nl_tab(1, 2) . $fieldRelationshipModel . $this->serviceGenerator->infy_nl_tab(1, 2) . $fieldRelationshipModelCurrent, $templateData);
        $templateData = str_replace('{{DATE_TIME}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{TABLE_NAME_TITLE}}', self::REF_UPPER . $model . $modelCurrent, $templateData);
        $templateData = str_replace('{{MODEL_CLASS}}', self::REF_UPPER . $model . $modelCurrent, $templateData);
        $templateData = str_replace('{{FIELDS}}', "'" . $fieldModel . "' => " . '$faker->randomElement(' . $paramModel . '),' . $this->serviceGenerator->infy_nl_tab(1, 4) . "'" . $fieldModelCurrent . "' => " . '$faker->randomElement(' . $paramModelCurrent . '),', $templateData);
        $templateData = str_replace($notDelete['seeder'], '', $templateData);
        $path = config('generator.path.laravel.seeder');
        $this->serviceFile->createFile($path, $fileName, $templateData);

        return $templateData;
    }

    private function _createFileAll($namePath, $model, $templateDataReal)
    {
        $path = config('generator.path.laravel.' . $namePath);
        $fileName = $path . $model . '.php';
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
    }

    private function replaceTemplateHeading($fieldsGenerate, $tab)
    {
        return $this->serviceGenerator->infy_nl_tab(1, $tab) . implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate) . $this->serviceGenerator->infy_nl_tab(1, $tab);
    }

    private function replaceTemplate($fieldsGenerate, $tabStart, $tabEnd, $tabFields, $space = 2)
    {
        return $this->serviceGenerator->infy_nl_tab(1, $tabStart) . implode($this->serviceGenerator->infy_nl_tab(1, $tabFields), $fieldsGenerate) . $this->serviceGenerator->infy_nl_tab(1, $tabEnd, $space);
    }

    private function _generateSelect($funcName, $field, $column, $relationship)
    {
        $pathTemplate = 'Forms/';
        if ($relationship === $this->relationship['belongs_to_many']) {
            $formTemplate = $this->serviceGenerator->get_template('selectMTM', $pathTemplate, 'vuejs');
        } else {
            $formTemplate = $this->serviceGenerator->get_template('select', $pathTemplate, 'vuejs');
        }
        $formTemplate = str_replace('{{$FORM_FIELD$}}', $field, $formTemplate);
        $formTemplate = str_replace('{{$LABEL_NAME_INPUT$}}', '$t(\'route.' . $funcName . '\')', $formTemplate);
        $formTemplate = str_replace('{{$PROP_NAME$}}', 'prop="' . $field . '"', $formTemplate);
        $formTemplate = str_replace('{{$LIST_SELECT$}}', \Str::camel($funcName), $formTemplate);
        $formTemplate = str_replace('{{$LABEL_OPTION$}}', 'item.' . $column, $formTemplate);
        $formTemplate = str_replace('{{$VALUE_OPTION$}}', 'item.id', $formTemplate);

        return $formTemplate;
    }

    private function getHandlerTemplate($nameForm)
    {
        $pathTemplate = 'Handler/';
        $templateData = $this->serviceGenerator->get_template($nameForm, $pathTemplate, 'vuejs');

        return $templateData;
    }
}
