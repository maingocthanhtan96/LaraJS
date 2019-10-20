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

    CONST DB_TYPE_INTEGER = 'integer';
    CONST DB_TYPE_NUMBER = 'number';
    CONST DB_TYPE_BOOLEAN = 'boolean';
    CONST DB_TYPE_STRING = 'string';

    public function __construct($fields, $model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.swagger');
        $this->notDelete = config('generator.not_delete.laravel.swagger');

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
        $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateFields($fields), 1, $templateData);

        //create sort delete
        $fileName = $model['name'] . '.php';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);

        return $templateData;
    }

    private function generateFields($fields)
    {
        $fieldsGenerate = [];
        $dbType = config('generator.db_type');
        $configDefaultValue = config('generator.default_value');

        foreach ($fields as $index => $field) {
            if ($index > 0) {
                if ($field['default_value'] === $configDefaultValue['none']) {
                    $defaultValue = 'NONE';
                } else if ($field['default_value'] === $configDefaultValue['null']) {
                    $defaultValue = 'NULL';
                } else {
                    $defaultValue = $field['as_define'];
                }
                $templateProperty = $this->serviceGenerator->get_template("property", 'Swagger/');
                $templateProperty = str_replace('{{FIELD}}', $field['field_name'], $templateProperty);
                $templateProperty = str_replace('{{FIELD_TRANS}}', $field['field_name_trans'], $templateProperty);
                $templateProperty = str_replace('{{DEFAULT_VALUE}}', $defaultValue, $templateProperty);
                switch ($field['db_type']) {
                    case $dbType['integer']:
                    case $dbType['bigInteger']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_INTEGER, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 0, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['float']:
                    case $dbType['double']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_NUMBER, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 0.1, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['boolean']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_BOOLEAN, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 0, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['date']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '1996-02-17', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['dateTime']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '1996-02-17 12:00:00', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['time']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '12:00:00', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['year']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '1996', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['string']:
                    case $dbType['text']:
                    case $dbType['longtext']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', 'string', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['file']:
                        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_STRING, $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', "['https://lorempixel.com/150/150/?77253', 'https://lorempixel.com/150/150/?77253']", $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $dbType['enum']:
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
                    case $dbType['json']:
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
