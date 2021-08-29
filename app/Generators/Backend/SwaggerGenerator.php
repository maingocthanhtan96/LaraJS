<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use Carbon\Carbon;

class SwaggerGenerator extends BaseGenerator
{
    public const FIELD_ID = 'id';
    /** @var string */
    protected $dbType;

    /** @var string */
    protected $configDefaultValue;

    public function __construct($fields, $model)
    {
        parent::__construct();
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
        $templateData = $this->serviceGenerator->get_template('swagger', $pathTemplate);
        $templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
        // Generate JsonContent
        $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['json_content'], $this->generateFields($fields, true), 0, $templateData, 0);

        $templateData = str_replace('{{MODEL_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{RESOURCE}}', $this->serviceGenerator->urlResource($model['name']), $templateData);
        $templateTimestamps = '';
        if ($this->serviceGenerator->getOptions(config('generator.model.options.timestamps'), $model['options'])) {
            $templateTimestamps = $this->serviceGenerator->get_template('timestamps', $pathTemplate);
        }
        // SoftDeletes
        $templateData = str_replace($this->notDelete['timestamps'], $templateTimestamps, $templateData);
        $templateSoftDeletes = '';
        if ($this->serviceGenerator->getOptions(config('generator.model.options.soft_deletes'), $model['options'])) {
            $templateSoftDeletes = $this->serviceGenerator->get_template('SoftDeletes', $pathTemplate);
        }
        // SoftDeletes
        $templateData = str_replace($this->notDelete['soft_deletes'], $templateSoftDeletes, $templateData);
        // Required
        $fieldRequired = \Arr::pluck($fields, 'default_value', 'field_name');

        $fieldRequires = '';
        foreach ($fieldRequired as $field => $default) {
            if ($field === self::FIELD_ID) {
                continue;
            }
            if ($default === $this->configDefaultValue['none']) {
                $fieldRequires .= '"' . $field . '",';
            }
        }
        if ($fieldRequires) {
            $templateData = str_replace('{{REQUIRED_FIELDS}}', '{"' . self::FIELD_ID . '", ' . rtrim($fieldRequires, ', ') . '}', $templateData);
        } else {
            $templateData = str_replace('{{REQUIRED_FIELDS}}', '{"' . self::FIELD_ID . '"}', $templateData);
        }
        // end required

        $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateFields($fields), 1, $templateData);
        //create sort delete
        $fileName = $model['name'] . '.php';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);

        return $templateData;
    }

    private function generateFields($fields, $propertyJson = false)
    {
        $fieldsGenerate = [];
        $faker = \Faker\Factory::create();

        foreach ($fields as $index => $field) {
            if ($index > 0) {
                if ($field['default_value'] === $this->configDefaultValue['none']) {
                    $defaultValue = 'NONE';
                } elseif ($field['default_value'] === $this->configDefaultValue['null']) {
                    $defaultValue = 'NULL';
                } else {
                    $defaultValue = $field['as_define'];
                }
                if ($propertyJson) {
                    $templateProperty = $this->serviceGenerator->get_template('propertyJson', 'Swagger/');
                } else {
                    $templateProperty = $this->serviceGenerator->get_template('property', 'Swagger/');
                }
                $templateProperty = str_replace('{{FIELD}}', $field['field_name'], $templateProperty);
                $templateProperty = str_replace('{{DEFAULT_VALUE}}', $defaultValue, $templateProperty);
                switch ($field['db_type']) {
                    case $this->dbType['integer']:
                    case $this->dbType['bigInteger']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', $faker->numberBetween(1000, 9000), $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['float']:
                    case $this->dbType['double']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', $faker->randomFloat(2, 1000, 9000), $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['boolean']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', $faker->numberBetween(0, 1), $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['date']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $this->dbType['date'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', Carbon::now()->toDateString(), $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['dateTime']:
                    case $this->dbType['timestamp']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', Carbon::now()->toDateTimeString(), $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['time']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', Carbon::now()->toTimeString(), $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['year']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', Carbon::now()->year, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['string']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', $faker->name, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['text']:
                    case $this->dbType['longtext']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', $faker->paragraph, $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['file']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', "['https://lorempixel.com/150/150/?77253', 'https://lorempixel.com/150/150/?77253']", $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['enum']:
                        $enum = '';
                        foreach ($field['enum'] as $keyEnum => $value) {
                            if ($keyEnum === count($field['enum']) - 1) {
                                $enum .= '"' . $value . '"';
                            } else {
                                $enum .= '"' . $value . '",';
                            }
                        }
                        if ($propertyJson) {
                            $templateProperty = $this->serviceGenerator->get_template('propertyEnumJson', 'Swagger/');
                        } else {
                            $templateProperty = $this->serviceGenerator->get_template('propertyEnum', 'Swagger/');
                        }
                        $templateProperty = str_replace('{{FIELD}}', $field['field_name'], $templateProperty);
                        $templateProperty = str_replace('{{DEFAULT_VALUE}}', $defaultValue, $templateProperty);
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', \Arr::random($field['enum']), $templateProperty);
                        $templateProperty = str_replace('{{ENUM}}', '{' . $enum . '}', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                    case $this->dbType['json']:
                        $templateProperty = str_replace('{{DB_TYPE}}', $field['db_type'], $templateProperty);
                        $templateProperty = str_replace('{{EXAMPLE}}', '[{}]', $templateProperty);
                        $fieldsGenerate[] = $templateProperty;
                        break;
                }
            }
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 1), $fieldsGenerate);
    }
}
