<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;
use Carbon\Carbon;

class ControllerUpdateGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    const QS_ORDER = '$queryService->order';
    const QS_COLUMNS_SEARCH = '$queryService->columnSearch';

    public function __construct($model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.api_controller');

        $this->generate($model, $updateFields);
    }

    private function generate($model, $updateFields)
    {
        $fileName = $model['name'] . 'Controller.php';
        $templateDataReal = $this->serviceGenerator->getFile('api_controller', 'laravel', $fileName);
        $templateDataReal = $this->generateFieldsRename($updateFields['renameFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsChange($updateFields['changeFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsUpdate($updateFields['updateFields'], $templateDataReal);
        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
    }

    private function generateFieldsRename($renameFields, $templateDataReal)
    {
        foreach ($renameFields as $rename) {
            $templateDataReal = str_replace(
                "'" . $rename['field_name_old']['field_name'] . "'",
                "'" . $rename['field_name_new']['field_name'] . "'",
                $templateDataReal,
            );
        }
        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        foreach ($dropFields as $drop) {
            $name = "'" . $drop['field_name'] . "',";
            $templateDataReal = $this->checkComma($name, $drop['field_name'], $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsChange($changeFields, $templateDataReal)
    {
        return $this->changeUpdateFields($changeFields, $templateDataReal);
    }

    private function checkComma($name, $drop, $templateDataReal)
    {
        if (\Str::contains($templateDataReal, "$name")) {
            if (\Str::contains($templateDataReal, "$name ")) {
                $templateDataReal = str_replace("'" . $drop . "', ", '', $templateDataReal);
            } else {
                $templateDataReal = str_replace("'" . $drop . "',", '', $templateDataReal);
            }
        } else {
            $templateDataReal = str_replace("'" . $drop . "'", '', $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsUpdate($updateFields, $templateDataReal)
    {
        if (empty($updateFields)) {
            return $templateDataReal;
        }

        $templateColumns = $this->serviceGenerator->searchTemplate(
            self::QS_ORDER,
            '];',
            strlen(self::QS_ORDER) + 4,
            -strlen(self::QS_ORDER) - 4,
            $templateDataReal,
            self::QS_ORDER . ' =',
        );
        $templateColumnsSearch = $this->serviceGenerator->searchTemplate(
            self::QS_COLUMNS_SEARCH,
            '];',
            strlen(self::QS_COLUMNS_SEARCH) + 4,
            -strlen(self::QS_COLUMNS_SEARCH) - 4,
            $templateDataReal,
            self::QS_COLUMNS_SEARCH . ' =',
        );

        if (!$templateColumns || !$templateColumnsSearch) {
            return $templateDataReal;
        }

        $comma = ', ';
        $commaSearch = ', ';
        $columns = '';
        $columnsSearch = '';
        if (\Str::endsWith($templateColumns, ',') || \Str::endsWith($templateColumns, ', ')) {
            $comma = '';
        }
        foreach ($updateFields as $index => $update) {
            if ($update['sort']) {
                $columns .= $comma . "'" . $update['field_name'] . "'";
            }
        }
        if (\Str::endsWith($templateColumnsSearch, ',') || \Str::endsWith($templateColumnsSearch, ', ')) {
            $commaSearch = '';
        }
        foreach ($updateFields as $index => $update) {
            if ($update['search']) {
                $columnsSearch .= $commaSearch . "'" . $update['field_name'] . "'";
            }
        }
        $selfColumns = self::QS_ORDER;
        $selfColumnsSearch = self::QS_COLUMNS_SEARCH;
        $templateDataReal = str_replace(
            "$selfColumns = [" . $templateColumns . ']',
            "$selfColumns = [" . $templateColumns . $columns . ']',
            $templateDataReal,
        );
        $templateDataReal = str_replace(
            "$selfColumnsSearch = [" . $templateColumnsSearch . ']',
            "$selfColumnsSearch = [" . $templateColumnsSearch . $columnsSearch . ']',
            $templateDataReal,
        );

        return $templateDataReal;
    }

    private function changeUpdateFields($changeFields, $templateDataReal)
    {
        if (empty($changeFields)) {
            return $templateDataReal;
        }
        $fieldsGeneratorColumn = [];
        $fieldsGeneratorColumnSearch = [];
        $templateColumns = $this->serviceGenerator->searchTemplate(
            self::QS_ORDER,
            '];',
            strlen(self::QS_ORDER) + 4,
            -strlen(self::QS_ORDER) - 4,
            $templateDataReal,
            self::QS_ORDER . ' =',
        );

        $templateColumnsSearch = $this->serviceGenerator->searchTemplate(
            self::QS_COLUMNS_SEARCH,
            '];',
            strlen(self::QS_COLUMNS_SEARCH) + 4,
            -strlen(self::QS_COLUMNS_SEARCH) - 4,
            $templateDataReal,
            self::QS_COLUMNS_SEARCH . ' =',
        );

        if (!$templateColumns || !$templateColumnsSearch) {
            return $templateDataReal;
        }

        $arrayColumns = explode(',', $templateColumns);
        $arrayColumnsSearch = explode(',', $templateColumnsSearch);
        $arrayChange = \Arr::pluck($changeFields, 'field_name');

        foreach ($changeFields as $change) {
            foreach ($arrayColumns as $sort) {
                $sort = trim($sort);
                $trimSort = $this->serviceGenerator->trimQuotes($sort);
                if ($change['field_name'] === $trimSort) {
                    if ($change['sort']) {
                        $fieldsGeneratorColumn[] = "'" . $change['field_name'] . "'";
                    }
                } else {
                    $nameTrimSort = "'" . $trimSort . "'";
                    if (!in_array($nameTrimSort, $fieldsGeneratorColumn) && !in_array($nameTrimSort, $arrayChange)) {
                        $fieldsGeneratorColumn[] = $nameTrimSort;
                    }
                }
            }
            foreach ($arrayColumnsSearch as $search) {
                $search = trim($search);
                $trimSort = $this->serviceGenerator->trimQuotes($search);
                if ($change['field_name'] === $trimSort) {
                    if ($change['search']) {
                        $fieldsGeneratorColumnSearch[] = "'" . $change['field_name'] . "'";
                    }
                } else {
                    $nameTrimSort = "'" . $trimSort . "'";
                    if (
                        !in_array($nameTrimSort, $fieldsGeneratorColumnSearch) &&
                        !in_array($nameTrimSort, $arrayChange)
                    ) {
                        $fieldsGeneratorColumnSearch[] = $nameTrimSort;
                    }
                }
            }
        }

        $selfColumns = self::QS_ORDER;
        $selfColumnsSearch = self::QS_COLUMNS_SEARCH;
        $templateDataReal = str_replace(
            "$selfColumns = [" . $templateColumns . ']',
            "$selfColumns = [" .
                implode($this->serviceGenerator->infy_nl_tab(0, 0) . ', ', $fieldsGeneratorColumn) .
                ']',
            $templateDataReal,
        );
        $templateDataReal = str_replace(
            "$selfColumnsSearch = [" . $templateColumnsSearch . ']',
            "$selfColumnsSearch = [" .
                implode($this->serviceGenerator->infy_nl_tab(0, 0) . ', ', $fieldsGeneratorColumnSearch) .
                ']',
            $templateDataReal,
        );

        return $templateDataReal;
    }
}
