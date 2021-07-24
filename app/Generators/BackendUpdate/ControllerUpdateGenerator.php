<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;
use Carbon\Carbon;

class ControllerUpdateGenerator extends BaseGenerator
{
    /** @var GeneratorService $service */
    public GeneratorService $serviceGenerator;

    /** @var FileService $service */
    public FileService $serviceFile;

    /** @var string */
    public $path;

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
            $templateDataReal = str_replace("'" . $rename['field_name_old']['field_name'] . "'", "'" . $rename['field_name_new']['field_name'] . "'", $templateDataReal);
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

        $templateColumnsSearch = $this->serviceGenerator->searchTemplate(
            self::QS_COLUMNS_SEARCH,
            '];',
            strlen(self::QS_COLUMNS_SEARCH) + 4,
            -strlen(self::QS_COLUMNS_SEARCH) - 4,
            $templateDataReal,
            self::QS_COLUMNS_SEARCH . ' ='
        );

        if (!$templateColumnsSearch) {
            return $templateDataReal;
        }

        $comma = ', ';
        $commaSearch = ', ';
        $columns = '';
        $columnsSearch = '';
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
        $selfColumnsSearch = self::QS_COLUMNS_SEARCH;
        return str_replace("$selfColumnsSearch = [" . $templateColumnsSearch . ']', "$selfColumnsSearch = [" . $templateColumnsSearch . $columnsSearch . ']', $templateDataReal);
    }

    private function changeUpdateFields($changeFields, $templateDataReal)
    {
        if (empty($changeFields)) {
            return $templateDataReal;
        }
        $fieldsGeneratorColumnSearch = [];

        $templateColumnsSearch = $this->serviceGenerator->searchTemplate(
            self::QS_COLUMNS_SEARCH,
            '];',
            strlen(self::QS_COLUMNS_SEARCH) + 4,
            -strlen(self::QS_COLUMNS_SEARCH) - 4,
            $templateDataReal,
            self::QS_COLUMNS_SEARCH . ' ='
        );

        if (!$templateColumnsSearch) {
            return $templateDataReal;
        }

        $arrayColumnsSearch = explode(',', $templateColumnsSearch);
        $arrayChange = \Arr::pluck($changeFields, 'field_name');

        foreach ($changeFields as $change) {
            foreach ($arrayColumnsSearch as $search) {
                $search = trim($search);
                $trimSort = $this->serviceGenerator->trimQuotes($search);
                if ($change['field_name'] === $trimSort) {
                    if ($change['search']) {
                        $fieldsGeneratorColumnSearch[] = "'" . $change['field_name'] . "'";
                    }
                } else {
                    $nameTrimSort = "'" . $trimSort . "'";
                    if (!in_array($nameTrimSort, $fieldsGeneratorColumnSearch) && !in_array($nameTrimSort, $arrayChange)) {
                        $fieldsGeneratorColumnSearch[] = $nameTrimSort;
                    }
                }
            }
        }

        $selfColumnsSearch = self::QS_COLUMNS_SEARCH;
        return str_replace(
            "$selfColumnsSearch = [" . $templateColumnsSearch . ']',
            "$selfColumnsSearch = [" . implode($this->serviceGenerator->infy_nl_tab(0, 0) . ', ', $fieldsGeneratorColumnSearch) . ']',
            $templateDataReal
        );
    }
}
