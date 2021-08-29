<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;

class ModelUpdateGenerator extends BaseGenerator
{
    public function __construct($model, $updateFields)
    {
        parent::__construct();
        $this->path = config('generator.path.laravel.model');
        $this->notDelete = config('generator.not_delete.laravel.model');

        $this->generate($updateFields, $model);
    }

    private function generate($updateFields, $model)
    {
        $templateDataReal = $this->serviceGenerator->getFile('model', 'laravel', $model['name'] . '.php');
        $templateDataReal = $this->generateUpdateFields($updateFields['updateFields'], $templateDataReal);
        $checkGenerateYear = $this->generateYear($updateFields);
        if ($checkGenerateYear) {
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['cats'], $checkGenerateYear, 2, $templateDataReal);
        }
        $templateDataReal = $this->generateFieldsRename($updateFields['renameFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $templateDataReal);

        $fileName = $this->path . $model['name'] . '.php';
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
    }

    private function generateUpdateFields($updateFields, $templateDataReal)
    {
        if (!$updateFields) {
            return $templateDataReal;
        }
        $fieldsGenerate = [];
        $fieldAble = 'protected $fillable = [';
        $template = $this->serviceGenerator->searchTemplate($fieldAble, '];', strlen($fieldAble), -strlen($fieldAble), $templateDataReal);
        if (!$template) {
            return $templateDataReal;
        }
        $arTemplate = explode(',', trim($template));

        foreach ($arTemplate as $tpl) {
            if (strlen($tpl) > 0) {
                $fieldsGenerate[] = trim($tpl) . ',';
            }
        }
        foreach ($updateFields as $field) {
            $fieldsGenerate[] = "'" . $field['field_name'] . "',";
        }
        $implodeString = implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
        return str_replace($template, $this->serviceGenerator->infy_nl_tab(1, 2) . $implodeString . $this->serviceGenerator->infy_nl_tab(1, 1), $templateDataReal);
    }

    private function generateFieldsRename($renameFields, $templateDataReal)
    {
        foreach ($renameFields as $rename) {
            $templateDataReal = str_replace("'" . $rename['field_name_old']['field_name'] . "',", "'" . $rename['field_name_new']['field_name'] . "',", $templateDataReal);
        }
        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        foreach ($dropFields as $drop) {
            $templateDataReal = str_replace("'" . $drop['field_name'] . "',", '', $templateDataReal);
            $templateDataReal = str_replace("'" . $drop['field_name'] . "' => 'string',", '', $templateDataReal);
        }
        return $templateDataReal;
    }

    private function generateYear($updateFields)
    {
        $fieldsGenerate = [];
        $dbType = config('generator.db_type');
        foreach ($updateFields['updateFields'] as $index => $field) {
            if ($field['db_type'] === $dbType['year']) {
                $name = $field['field_name'];
                $fieldsGenerate[] = "'$name' => 'string',";
            }
        }

        foreach ($updateFields['changeFields'] as $change) {
            if ($change['db_type'] === $dbType['year']) {
                $name = $change['field_name'];
                $fieldsGenerate[] = "'$name' => 'string',";
            }
        }
        return implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
    }
}
