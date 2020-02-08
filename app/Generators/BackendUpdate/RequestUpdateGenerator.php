<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

class RequestUpdateGenerator extends BaseGenerator
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

    const COLUMNS = 'return [';
    const CHAR = '];';
    const REQUIRED = 'required';
    const NULLABLE = 'nullable';

    public function __construct($generator, $model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.request');
        $this->notDelete = config('generator.not_delete.laravel.request');
        $this->dbType = config('generator.db_type');
        $this->configDefaultValue = config('generator.default_value');

        $this->generate($generator, $model, $updateFields);
    }

    private function generate($generator, $model, $updateFields)
    {
        $fileName = 'Store' . $model['name'] . 'Request.php';
        $templateDataReal = $this->serviceGenerator->getFile(
            'request',
            'laravel',
            $fileName
        );
        $templateDataReal = $this->generateFieldsDrop(
            $updateFields['dropFields'],
            $templateDataReal
        );
        $templateDataReal = $this->generateFieldsRename(
            $updateFields['renameFields'],
            $templateDataReal
        );
        $templateDataReal = $this->generateFieldsChange(
            $generator,
            $updateFields['changeFields'],
            $templateDataReal
        );
        $templateDataReal = $this->generateFieldsUpdate(
            $updateFields['updateFields'],
            $templateDataReal
        );

        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);

        return $templateDataReal;
    }

    private function generateFieldsRename($renameFields, $templateDataReal)
    {
        foreach ($renameFields as $rename) {
            $templateDataReal = str_replace(
                "'" . $rename['field_name_old']['field_name'] . "'",
                "'" . $rename['field_name_new']['field_name'] . "'",
                $templateDataReal
            );
        }

        return $templateDataReal;
    }

    private function generateFieldsChange(
        $generator,
        $changeFields,
        $templateDataReal
    ) {
        if (empty($changeFields)) {
            return $templateDataReal;
        }

        $fieldsGenerator = [];
        $templateColumns = $this->serviceGenerator->searchTemplate(
            self::COLUMNS,
            self::CHAR,
            strlen(self::COLUMNS) + 4,
            -4 - strlen(self::COLUMNS),
            $templateDataReal
        );
        $arrayColumns = explode(PHP_EOL, $templateColumns);
        $arrayChange = \Arr::pluck($changeFields, 'field_name');

        $formFields = json_decode($generator->field, true);
        $dataOld = [];
        foreach ($formFields as $index => $field) {
            if ($index > 0) {
                $dataOld[$field['id']]['db_type'] = $field['db_type'];
                $dataOld[$field['id']]['default_value'] =
                    $field['default_value'];
                $dataOld[$field['id']]['enum'] = $field['enum'];
            }
        }
        foreach ($changeFields as $change) {
            foreach ($arrayColumns as $key => $req) {
                if (trim($req) && trim($req) !== $this->notDelete['rule']) {
                    list($keyField, $valField) = explode(' => ', $req);
                    $keyField = trim($keyField);
                    $valField = trim($valField);
                    $keyField = $this->serviceGenerator->trimQuotes($keyField);

                    if ($change['field_name'] === $keyField) {
                        if (
                            $change['default_value'] ===
                            $this->configDefaultValue['none']
                        ) {
                            $required = 'required';
                        } else {
                            $required = 'nullable';
                        }
                        if (
                            $dataOld[$change['id']]['default_value'] ===
                            $this->configDefaultValue['none']
                        ) {
                            $requiredOld = 'required';
                        } else {
                            $requiredOld = 'nullable';
                        }
                        if (
                            $dataOld[$change['id']]['db_type'] ===
                            $this->dbType['string']
                        ) {
                            preg_match(
                                '/max:[0-9]{0,3}/im',
                                $valField,
                                $matches
                            );
                            if (isset($matches[0])) {
                                $valField = str_replace(
                                    $matches[0],
                                    "max:{$change['length_varchar']}",
                                    $valField
                                );
                            }
                        }
                        $valField = str_replace(
                            $requiredOld,
                            $required,
                            $valField
                        );
                        if (
                            $dataOld[$change['id']]['db_type'] !==
                            $change['db_type']
                        ) {
                            $valField = str_replace(
                                $this->changeDBType(
                                    $dataOld[$change['id']]['db_type'],
                                    $dataOld[$change['id']]['enum']
                                ),
                                $this->changeDBType(
                                    $change['db_type'],
                                    $change['enum']
                                ),
                                $valField
                            );
                            if (
                                $change['db_type'] !== $this->dbType['string']
                            ) {
                                preg_match(
                                    '/\|?max:[0-9]{0,3}\|?/im',
                                    $valField,
                                    $matches
                                );
                                if (isset($matches[0])) {
                                    $valField = str_replace(
                                        $matches[0],
                                        '',
                                        $valField
                                    );
                                }
                            }
                        }
                        $fieldsGenerator[] = "'" . $keyField . "' => $valField";
                    } else {
                        $value = "'" . $keyField . "' => $valField";
                        if (
                            !in_array($value, $fieldsGenerator) &&
                            !in_array($keyField, $arrayChange)
                        ) {
                            $fieldsGenerator[] = $value;
                        }
                    }
                }
            }
        }
        $fieldsGenerator[] =
            $this->notDelete['rule'] .
            $this->serviceGenerator->infy_nl_tab(1, 2);
        $templateDataReal = str_replace(
            $templateColumns,
            $this->serviceGenerator->infy_nl_tab(0, 2) .
                ' ' .
                implode(
                    $this->serviceGenerator->infy_nl_tab(1, 3),
                    $fieldsGenerator
                ),
            $templateDataReal
        );

        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        if (empty($dropFields)) {
            return $templateDataReal;
        }

        $fieldsGenerator = [];
        $templateColumns = $this->serviceGenerator->searchTemplate(
            self::COLUMNS,
            self::CHAR,
            strlen(self::COLUMNS) + 4,
            -4 - strlen(self::COLUMNS),
            $templateDataReal
        );
        $arrayColumns = explode(PHP_EOL, $templateColumns);
        $arrayChange = \Arr::pluck($dropFields, 'field_name');

        foreach ($dropFields as $drop) {
            foreach ($arrayColumns as $key => $req) {
                if (trim($req) && trim($req) !== $this->notDelete['rule']) {
                    list($keyField, $valField) = explode(' => ', $req);
                    $keyField = trim($keyField);
                    $valField = trim($valField);
                    $keyField = $this->serviceGenerator->trimQuotes($keyField);
                    $value = "'" . $keyField . "' => $valField";
                    if ($keyField !== $drop['field_name']) {
                        if (
                            !in_array($value, $fieldsGenerator) &&
                            !in_array($keyField, $arrayChange)
                        ) {
                            $fieldsGenerator[] = $value;
                        }
                    }
                }
            }
        }
        $fieldsGenerator[] =
            $this->notDelete['rule'] .
            $this->serviceGenerator->infy_nl_tab(1, 2);
        $templateDataReal = str_replace(
            $templateColumns,
            $this->serviceGenerator->infy_nl_tab(0, 2) .
                ' ' .
                implode(
                    $this->serviceGenerator->infy_nl_tab(1, 3),
                    $fieldsGenerator
                ),
            $templateDataReal
        );

        return $templateDataReal;
    }

    public function generateFieldsUpdate($updateFields, $templateDataReal)
    {
        if (empty($updateFields)) {
            return $templateDataReal;
        }

        $templateDataReal = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['rule'],
            $this->generateFields($updateFields),
            3,
            $templateDataReal
        );

        return $templateDataReal;
    }

    private function generateFields($fields)
    {
        $fieldsGenerate = [];

        foreach ($fields as $index => $field) {
            if ($field['default_value'] === $this->configDefaultValue['null']) {
                $required = 'nullable';
            } else {
                $required = 'required';
            }
            switch ($field['db_type']) {
                case $this->dbType['integer']:
                case $this->dbType['bigInteger']:
                case $this->dbType['float']:
                case $this->dbType['double']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|numeric'" .
                        ',';
                    break;
                case $this->dbType['boolean']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|boolean'" .
                        ',';
                    break;
                case $this->dbType['date']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|date_format:Y-m-d'" .
                        ',';
                    break;
                case $this->dbType['dateTime']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|date_format:Y-m-d H:i:s'" .
                        ',';
                    break;
                case $this->dbType['time']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|date_format:H:i:s'" .
                        ',';
                    break;
                case $this->dbType['year']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|date_format:Y'" .
                        ',';
                    break;
                case $this->dbType['string']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|string|max:{$field['length_varchar']}'" .
                        ',';
                    break;
                case $this->dbType['text']:
                case $this->dbType['longtext']:
                case $this->dbType['file']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|string'" .
                        ',';
                    break;
                case $this->dbType['enum']:
                    $enum = 'in:';
                    foreach ($field['enum'] as $keyEnum => $value) {
                        if ($keyEnum === count($field['enum']) - 1) {
                            $enum .= "$value";
                        } else {
                            $enum .= "$value" . ',';
                        }
                    }
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|$enum'" .
                        ',';
                    break;
                case $this->dbType['json']:
                    $fieldsGenerate[] =
                        "'" .
                        $field['field_name'] .
                        "'" .
                        ' => ' .
                        "'$required|json'" .
                        ',';
                    break;
            }
        }

        return implode(
            $this->serviceGenerator->infy_nl_tab(1, 3),
            $fieldsGenerate
        );
    }

    private function changeDBType($dbType, $enums = [])
    {
        $field = '';
        switch ($dbType) {
            case $this->dbType['integer']:
            case $this->dbType['bigInteger']:
            case $this->dbType['float']:
            case $this->dbType['double']:
                $field = 'numeric';
                break;
            case $this->dbType['boolean']:
                $field = "boolean";
                break;
            case $this->dbType['date']:
                $field = "date_format:Y-m-d";
                break;
            case $this->dbType['dateTime']:
                $field = "date_format:Y-m-d H:i:s";
                break;
            case $this->dbType['time']:
                $field = "date_format:H:i:s";
                break;
            case $this->dbType['year']:
                $field = "date_format:Y";
                break;
            case $this->dbType['string']:
            case $this->dbType['text']:
            case $this->dbType['longtext']:
            case $this->dbType['file']:
                $field = "string";
                break;
            case $this->dbType['enum']:
                $enum = 'in:';
                foreach ($enums as $keyEnum => $value) {
                    if ($keyEnum === count($enums) - 1) {
                        $enum .= "$value";
                    } else {
                        $enum .= "$value" . ',';
                    }
                }
                $field = "$enum";
                break;
            case $this->dbType['json']:
                $field = "json";
                break;
        }

        return $field;
    }
}
