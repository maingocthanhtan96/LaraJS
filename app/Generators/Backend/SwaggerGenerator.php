<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class SwaggerGenerator extends BaseGenerator
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
    public $dbType;

    /** @var string */
    public $configDefaultValue;

    CONST DB_TYPE_INTEGER = 'integer';
    CONST DB_TYPE_FLOAT = 'float';
    CONST DB_TYPE_DOUBLE = 'double';
    CONST DB_TYPE_BOOLEAN = 'boolean';
    CONST DB_TYPE_STRING = 'string';

    CONST FIELD_ID = 'id';

    public function __construct($fields, $model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.swagger');
        $this->notDelete = config('generator.not_delete.laravel.swagger');
        $this->dbType = config('generator.db_type');
        $this->configDefaultValue = config('generator.default_value');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Swagger/';
        $templateData = $this->serviceGenerator->get_template("swagger", $pathTemplate);
        $templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{MODEL_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{RESOURCE}}', $this->serviceGenerator->urlResource($model['name']), $templateData);
        $templateSoftDeletes = '';
        if($this->serviceGenerator->getOptions(config('generator.model.options.sort_deletes'), $model['options'])) {
            $templateSoftDeletes = $this->serviceGenerator->get_template("SoftDeletes", $pathTemplate);
        }
        // SoftDeletes
        $templateData = str_replace($this->notDelete['soft_deletes'], $templateSoftDeletes, $templateData);
        // Required
        $fieldRequired = \Arr::pluck($fields, 'default_value', 'field_name');

        $fieldRequires = '';
        foreach($fieldRequired as $field => $default) {
            if($field === self::FIELD_ID) {
                continue;
            }
            if($default === $this->configDefaultValue['none']) {
                $fieldRequires .= '"'.$field.'"' . ', ';
            }
        }
        $templateData = str_replace('{{REQUIRED_FIELDS}}', '{'.rtrim($fieldRequires, ', ').'}', $templateData);
        // end required

        $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateFields($fields), 1, $templateData);
        //create sort delete
        $fileName = $model['name'] . '.php';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);

        return $templateData;
    }

    private function generateFields($fields)
    {
        $fieldsGenerate = [];

        foreach ($fields as $index => $field) {
            if ($index > 0) {
                if ($field['default_value'] === $this->configDefaultValue['none']) {
                    $defaultValue = 'NONE';
                } else if ($field['default_value'] === $this->configDefaultValue['null']) {
                    $defaultValue = 'NULL';
                } else {
                    $defaultValue = $field['as_define'];
                }
                $templateProperty = $this->serviceGenerator->get_template("property", 'Swagger/');
                $templateProperty = str_replace('{{FIELD}}', $field['field_name'], $templateProperty);
                $templateProperty = str_replace('{{FIELD_TRANS}}', $field['field_name_trans'], $templateProperty);
                $templateProperty = str_replace('{{DEFAULT_VALUE}}', $defaultValue, $templateProperty);
                switch ($field['db_type']) {
                    case $this->dbType['integer']:
                    case $this->dbType['bigInteger']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_INTEGER, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 0, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['float']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_FLOAT, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 0.1, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['double']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_DOUBLE, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 0.1, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['boolean']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_BOOLEAN, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 0, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['date']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '1996-02-17', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['dateTime']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '1996-02-17 12:00:00', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['time']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '12:00:00', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['year']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '1996', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['string']:
                    case $this->dbType['text']:
                    case $this->dbType['longtext']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 'string', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['file']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', "['https://lorempixel.com/150/150/?77253', 'https://lorempixel.com/150/150/?77253']", $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['enum']:
                        $enum = '';
                        foreach ($field['enum'] as $keyEnum => $value) {
                            if ($keyEnum === count($field['enum']) - 1) {
                                $enum .= "$value";
                            } else {
                                $enum .= "$value" . ',';
                            }
                        }
                        $templateProperty = $this->serviceGenerator->get_template("propertyEnum", 'Swagger/');
                        $templateProperty = str_replace('{{FIELD}}', $field['field_name'], $templateProperty);
                        $templateProperty = str_replace('{{FIELD_TRANS}}', $field['field_name_trans'], $templateProperty);
                        $templateProperty = str_replace('{{DEFAULT_VALUE}}', $defaultValue, $templateProperty);
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', \Arr::random($field['enum']), $templateProperty);
                        $templateProperty = str_replace('{{ENUM}}', "{" . $enum . "}", $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['json']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '[{}]', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                }
            }
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 1), $fieldsGenerate);
    }
}
