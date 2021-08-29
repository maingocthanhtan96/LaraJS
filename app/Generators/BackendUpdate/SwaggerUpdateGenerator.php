<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use Carbon\Carbon;

class SwaggerUpdateGenerator extends BaseGenerator
{
    public const FIELD_ID = 'id';

    public const OA_SCHEME = '@OA\Schema(';
    public const REQUIRED = 'required={';
    public const CHECK_CHAR_SCHEMA = '<###> @OA\Property(';

    /** @var string */
    protected $dbType;

    /** @var string */
    protected $configDefaultValue;

    public function __construct($generator, $model, $updateFields)
    {
        parent::__construct();
        $this->path = config('generator.path.laravel.swagger');
        $this->notDelete = config('generator.not_delete.laravel.swagger');
        $this->dbType = config('generator.db_type');
        $this->configDefaultValue = config('generator.default_value');

        $this->generate($generator, $model, $updateFields);
    }

    public function generateFieldsUpdate($updateFields, $templateDataReal)
    {
        if (!$updateFields) {
            return $templateDataReal;
        }
        // Required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);

        if (!$templateScheme || !$templateRequired) {
            return $templateDataReal;
        }
        $fieldRequired = \Arr::pluck($updateFields, 'default_value', 'field_name');

        $fieldRequires = '';
        foreach ($fieldRequired as $field => $default) {
            if ($field === self::FIELD_ID) {
                continue;
            }
            if ($default === $this->configDefaultValue['none']) {
                $fieldRequires .= '"' . $field . '",';
            }
        }
        $templateRequiredNew = $templateRequired . ', ' . $fieldRequires;
        $templateDataReal = str_replace($templateRequired, rtrim($templateRequiredNew, ', '), $templateDataReal);
        // end required
        $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateFields($updateFields), 1, $templateDataReal);
        return $this->serviceGenerator->replaceNotDelete($this->notDelete['json_content'], $this->generateFields($updateFields, true), 0, $templateDataReal);
    }

    public function changeDefault($field)
    {
        if ($field['default_value'] === $this->configDefaultValue['none']) {
            $defaultValue = 'NONE';
        } elseif ($field['default_value'] === $this->configDefaultValue['null']) {
            $defaultValue = 'NULL';
        } else {
            $defaultValue = $field['as_define'];
        }

        return 'default="' . $defaultValue . '"';
    }

    public function changeExample($field)
    {
        $example = '';
        $faker = \Faker\Factory::create();
        switch ($field['db_type']) {
            case $this->dbType['integer']:
            case $this->dbType['bigInteger']:
                $example = $faker->numberBetween(1000, 9000);
                break;
            case $this->dbType['float']:
            case $this->dbType['double']:
                $example = $faker->randomFloat(2, 1000, 9000);
                break;
            case $this->dbType['boolean']:
                $example = $faker->numberBetween(0, 1);
                break;
            case $this->dbType['date']:
                $example = Carbon::now()->toDateString();
                break;
            case $this->dbType['dateTime']:
            case $this->dbType['timestamp']:
                $example = Carbon::now()->toDateTimeString();
                break;
            case $this->dbType['time']:
                $example = Carbon::now()->toTimeString();
                break;
            case $this->dbType['year']:
                $example = Carbon::now()->year;
                break;
            case $this->dbType['string']:
                $example = $faker->name;
                break;
            case $this->dbType['text']:
            case $this->dbType['longtext']:
                $example = $faker->paragraph;
                break;
            case $this->dbType['file']:
                $example = "['https://lorempixel.com/150/150/?77253', 'https://lorempixel.com/150/150/?77253']";
                break;
            case $this->dbType['enum']:
                $example = \Arr::random($field['enum']);
                break;
            case $this->dbType['json']:
                $example = '[{}]';
                break;
        }

        return 'example="' . $example . '"';
    }

    private function generate($generator, $model, $updateFields)
    {
        $fileName = $model['name'] . '.php';
        $templateDataReal = $this->serviceGenerator->getFile('swagger', 'laravel', $fileName);
        $templateDataReal = $this->generateFieldsRename($updateFields['renameFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsChange($generator, $updateFields['changeFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsUpdate($updateFields['updateFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $templateDataReal);
        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);

        return $templateDataReal;
    }

    private function generateFieldsRename($renameFields, $templateDataReal)
    {
        if (!$renameFields) {
            return $templateDataReal;
        }
        // required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
        if (!$templateScheme || !$templateRequired) {
            return $templateDataReal;
        }
        //end required

        foreach ($renameFields as $rename) {
            $templateDataReal = str_replace('property="' . $rename['field_name_old']['field_name'] . '"', 'property="' . $rename['field_name_new']['field_name'] . '"', $templateDataReal);
            $templateRequiredNew = str_replace($rename['field_name_old']['field_name'], $rename['field_name_new']['field_name'], $templateRequired);
            $templateDataReal = str_replace($templateRequired, $templateRequiredNew, $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsChange($generator, $changeFields, $templateDataReal)
    {
        if (!$changeFields) {
            return $templateDataReal;
        }
        $formFields = json_decode($generator->field, true);
        // Required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
        if (!$templateScheme || !$templateRequired) {
            return $templateDataReal;
        }
        $arrayFields = explode(',', $templateRequired);
        $newFields = [];
        foreach ($arrayFields as $field) {
            $field = trim($field);
            $field = $this->serviceGenerator->trimQuotes($field);
            $newFields[] = $field; // auto None
        }
        // end required
        $dataOld = [];
        foreach ($formFields as $index => $field) {
            if ($index > 0) {
                $dataOld[$field['id']]['id'] = $field['id'];
                $dataOld[$field['id']]['db_type'] = $field['db_type'];
                $dataOld[$field['id']]['default_value'] = $field['default_value'];
                $dataOld[$field['id']]['enum'] = $field['enum'];
                $dataOld[$field['id']]['field_name'] = $field['field_name'];
                $dataOld[$field['id']]['as_define'] = $field['as_define'];
            }
        }
        foreach ($changeFields as $change) {
            if ($dataOld[$change['id']]['id'] === $change['id']) {
                // replace json content
                $searchPropertyJson = 'property="' . $change['field_name'] . '"';
                $templatePropertyJson = $this->serviceGenerator->searchTemplate($searchPropertyJson, '),', -strlen($searchPropertyJson) + 1, strlen($searchPropertyJson) + 1, $templateDataReal);
                if (!$templatePropertyJson) {
                    return false;
                }
                preg_match('/example=".*"/miU', $templatePropertyJson, $example);
                $example = reset($example);
                $templateJsonContentOld = $templatePropertyJson;
                $templatePropertyJson = str_replace($this->changeDefault($dataOld[$change['id']]), $this->changeDefault($change), $templatePropertyJson);

                $templatePropertyJson = str_replace($example, $this->changeExample($change), $templatePropertyJson);
                $templatePropertyJson = str_replace($this->changeDBType($dataOld[$change['id']]['db_type']), $this->changeDBType($change['db_type']), $templatePropertyJson);
                $templateDataReal = str_replace($templateJsonContentOld, $templatePropertyJson, $templateDataReal);

                // replace schema
                $templatePropertySchema = $this->serviceGenerator->searchTemplate(
                    self::CHECK_CHAR_SCHEMA . $searchPropertyJson,
                    '*/',
                    -strlen(self::CHECK_CHAR_SCHEMA) + 6,
                    strlen(self::CHECK_CHAR_SCHEMA) - 4,
                    $templateDataReal
                );
                if (!$templatePropertySchema) {
                    return false;
                }
                $templateSchemaOld = $templatePropertySchema;
                $templatePropertySchema = str_replace($this->changeDefault($dataOld[$change['id']]), $this->changeDefault($change), $templatePropertySchema);

                $templatePropertySchema = str_replace($this->changeDBType($dataOld[$change['id']]['db_type']), $this->changeDBType($change['db_type']), $templatePropertySchema);
                $templateDataReal = str_replace($templateSchemaOld, $templatePropertySchema, $templateDataReal);

                // required
                if ($change['default_value'] !== $this->configDefaultValue['none']) {
                    $key = array_search($change['field_name'], $newFields);
                    if ($key !== false) {
                        unset($newFields[$key]);
                    }
                } else {
                    $newFields[] = $change['field_name'];
                }
                // end required
            }
        }
        $fieldRequires = '';
        foreach (array_values($newFields) as $field) {
            $fieldRequires .= '"' . $field . '",';
        }
        if ($fieldRequires) {
            $templateDataReal = str_replace($templateRequired, rtrim($fieldRequires, ', '), $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        if (!$dropFields) {
            return $templateDataReal;
        }

        // required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
        if (!$templateScheme || !$templateRequired) {
            return $templateDataReal;
        }
        $arrayFields = explode(',', $templateRequired);
        $fieldRequiredDrop = \Arr::pluck($dropFields, 'field_name');
        $fieldRequires = '';
        foreach ($arrayFields as $field) {
            $field = trim($field);
            $field = $this->serviceGenerator->trimQuotes($field);
            if (!in_array($field, $fieldRequiredDrop)) {
                $fieldRequires .= '"' . $field . '",';
            }
        }
        $templateDataReal = str_replace($templateRequired, rtrim($fieldRequires, ', '), $templateDataReal);
        //end required
        foreach ($dropFields as $drop) {
            // drop json content
            $searchPropertyJson = 'property="' . $drop['field_name'] . '"';
            $templatePropertyJson = $this->serviceGenerator->searchTemplate($searchPropertyJson, '),', -strlen($searchPropertyJson) + 1, strlen($searchPropertyJson) + 1, $templateDataReal);
            if (!$templatePropertyJson) {
                return false;
            }
            $templateDataReal = str_replace($templatePropertyJson, '', $templateDataReal);

            // drop schema
            $templatePropertySchema = $this->serviceGenerator->searchTemplate(
                self::CHECK_CHAR_SCHEMA . $searchPropertyJson,
                '*/',
                -strlen(self::CHECK_CHAR_SCHEMA) + 6,
                strlen(self::CHECK_CHAR_SCHEMA) - 4,
                $templateDataReal
            );
            if (!$templatePropertySchema) {
                return false;
            }
            $templateDataReal = str_replace($templatePropertySchema, '', $templateDataReal);
        }

        return $templateDataReal;
    }

    private function changeDBType($dbType)
    {
        return 'type="' . $dbType . '"';
    }

    private function generateFields($fields, $propertyJson = false)
    {
        $fieldsGenerate = [];
        $faker = \Faker\Factory::create();

        foreach ($fields as $index => $field) {
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
            $templateProperty = str_replace('{{FIELD_TRANS}}', $field['field_name_trans'], $templateProperty);
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
                    $templateProperty = str_replace('{{FIELD_TRANS}}', $field['field_name_trans'], $templateProperty);
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

        return implode($this->serviceGenerator->infy_nl_tab(1, 1), $fieldsGenerate);
    }
}
