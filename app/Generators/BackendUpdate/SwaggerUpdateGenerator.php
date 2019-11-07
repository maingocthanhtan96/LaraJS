<?php

namespace App\Generators\BackendUpdate;

use App\Generators\Backend\SwaggerGenerator;
use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class SwaggerUpdateGenerator extends BaseGenerator
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

    CONST OA_SCHEME = '@OA\Schema(';
    CONST REQUIRED = 'required={';

    public function __construct($generator, $model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.swagger');
        $this->notDelete = config('generator.not_delete.laravel.swagger');
        $this->dbType = config('generator.db_type');
        $this->configDefaultValue = config('generator.default_value');

        $this->generate($generator, $model, $updateFields);
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
        if(empty($renameFields)) {
            return $templateDataReal;
        }
        // required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
        //end required

        foreach ($renameFields as $rename) {
            $templateDataReal = str_replace('title="' . $rename['field_name_old']['field_name_trans'] . '"', 'title="' . $rename['field_name_new']['field_name_trans'] . '"', $templateDataReal);
            $templateDataReal = str_replace('$' . $rename['field_name_old']['field_name'], '$' . $rename['field_name_new']['field_name'], $templateDataReal);
            $templateDataReal = str_replace('Field[' . $rename['field_name_old']['field_name'] . ']', 'Field[' . $rename['field_name_new']['field_name'] . ']', $templateDataReal);
            $templateRequiredNew = str_replace($rename['field_name_old']['field_name'], $rename['field_name_new']['field_name'], $templateRequired);
            $templateDataReal = str_replace($templateRequired, $templateRequiredNew, $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsChange($generator, $changeFields, $templateDataReal)
    {
        if (empty($changeFields)) {
            return $templateDataReal;
        }
        $formFields = json_decode($generator->field, true);
        // Required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
        $arrayFields = explode(',', $templateRequired);
        $newFields = [];
        foreach($arrayFields as $field) {
            $field = trim($field);
            $field = trim($field, '"');
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
                $searchStart = 'Field[' . $change['field_name'] . ']';
                $searchEnd = 'protected $' . $change['field_name'] . ';';
                $templateColumns = $this->serviceGenerator->searchTemplate($searchStart, $searchEnd, -strlen($searchStart), strlen($searchEnd) * 2, $templateDataReal);
                $templateColumnsOld = $templateColumns;
                $templateColumns = str_replace($this->changeDefault($dataOld[$change['id']]), $this->changeDefault($change), $templateColumns);
                $templateColumns = str_replace($this->changeExample($dataOld[$change['id']]), $this->changeExample($change), $templateColumns);
                $templateColumns = str_replace($this->changeDBType($dataOld[$change['id']]['db_type']), $this->changeDBType($change['db_type']), $templateColumns);
                // required
                if($change['default_value'] !== $this->configDefaultValue['none']) {
                    if (($key = array_search($change['field_name'], $newFields)) !== false) {
                        unset($newFields[$key]);
                    }
                } else {
                    $newFields[] = $change['field_name'];
                }
                // end required
                $templateDataReal = str_replace($templateColumnsOld, $templateColumns, $templateDataReal);
            }
        }
        $fieldRequires = '';
        foreach(array_values($newFields) as $field) {
            $fieldRequires .= '"'.$field.'"' . ', ';
        }
        if($fieldRequires) {
            $templateDataReal = str_replace($templateRequired, rtrim($fieldRequires, ', '), $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        if (empty($dropFields)) {
            return $templateDataReal;
        }

        // required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
        $arrayFields = explode(',', $templateRequired);
        $fieldRequiredDrop = \Arr::pluck($dropFields, 'field_name');
        $fieldRequires = '';
        foreach($arrayFields as $field) {
            $field = trim($field);
            $field = trim($field, '"');
            if(!in_array($field, $fieldRequiredDrop)) {
                $fieldRequires .= '"'.$field.'"' . ', ';
            }
        }
        $templateDataReal = str_replace($templateRequired, rtrim($fieldRequires, ', '), $templateDataReal);
        //end required
        foreach ($dropFields as $drop) {
            $searchStart = 'Field[' . $drop['field_name'] . ']';
            $searchEnd = 'protected $' . $drop['field_name'] . ';';
            $templateColumns = $this->serviceGenerator->searchTemplate($searchStart, $searchEnd, -strlen($searchStart), strlen($searchEnd) * 2, $templateDataReal);
            $templateDataReal = str_replace($templateColumns, '', $templateDataReal);
        }

        return $templateDataReal;
    }

    public function generateFieldsUpdate($updateFields, $templateDataReal)
    {
        if (empty($updateFields)) {
            return $templateDataReal;
        }
        // Required
        $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateDataReal);
        $templateRequired = $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
        $fieldRequired = \Arr::pluck($updateFields, 'default_value', 'field_name');

        $fieldRequires = '';
        foreach($fieldRequired as $field => $default) {
            if($field === self::FIELD_ID) {
                continue;
            }
            if($default === $this->configDefaultValue['none']) {
                $fieldRequires .= '"'.$field.'"' . ', ';
            }
        }
        $templateRequiredNew = $templateRequired . ', ' . $fieldRequires;
        $templateDataReal = str_replace($templateRequired, rtrim($templateRequiredNew, ', '), $templateDataReal);
        // end required
        $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateFields($updateFields), 1, $templateDataReal);

        return $templateDataReal;
    }

    private function changeDBType($dbType)
    {
        $field = '';
        switch ($dbType) {
            case $this->dbType['integer']:
            case $this->dbType['bigInteger']:
                $field = self::DB_TYPE_INTEGER;
                break;
            case $this->dbType['float']:
                $field = self::DB_TYPE_FLOAT;
                break;
            case $this->dbType['double']:
                $field = self::DB_TYPE_DOUBLE;
                break;
            case $this->dbType['boolean']:
                $field = self::DB_TYPE_BOOLEAN;
                break;
            case $this->dbType['date']:
            case $this->dbType['dateTime']:
            case $this->dbType['time']:
            case $this->dbType['year']:
            case $this->dbType['string']:
            case $this->dbType['text']:
            case $this->dbType['longtext']:
            case $this->dbType['file']:
            case $this->dbType['enum']:
            case $this->dbType['json']:
                $field = self::DB_TYPE_STRING;
                break;
        }

        return '@var ' . $field;
    }

    public function changeDefault($field)
    {
        if ($field['default_value'] === $this->configDefaultValue['none']) {
            $defaultValue = 'NONE';
        } else if ($field['default_value'] === $this->configDefaultValue['null']) {
            $defaultValue = 'NULL';
        } else {
            $defaultValue = $field['as_define'];
        }

        return 'default="' . $defaultValue . '"';
    }

    public function changeExample($field)
    {
        $example = '';
        switch ($field['db_type']) {
            case $this->dbType['integer']:
            case $this->dbType['bigInteger']:
            case $this->dbType['boolean']:
                $example = 0;
                break;
            case $this->dbType['float']:
            case $this->dbType['double']:
                $example = 0.1;
                break;
            case $this->dbType['date']:
                $example = '1996-02-17';
                break;
            case $this->dbType['dateTime']:
                $example = '1996-02-17 12:00:00';
                break;
            case $this->dbType['time']:
                $example = '12:00:00';
                break;
            case $this->dbType['year']:
                $example = '1996';
                break;
            case $this->dbType['string']:
            case $this->dbType['text']:
            case $this->dbType['longtext']:
                $example = "['https://lorempixel.com/150/150/?77253', 'https://lorempixel.com/150/150/?77253']";
                break;
            case $this->dbType['file']:
                $example = 'string';
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

    private function generateFields($fields)
    {
        $fieldsGenerate = [];

        foreach ($fields as $index => $field) {
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

        return implode($this->serviceGenerator->infy_nl_tab(1, 1), $fieldsGenerate);
    }
}