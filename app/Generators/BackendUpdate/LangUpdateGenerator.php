<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;

class LangUpdateGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    /** @var string */
    public $notDelete;

    public function __construct($model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.lang');
        $this->notDelete = config('generator.not_delete.laravel.model');

        $this->generate($model, $updateFields);
    }

    private function generate($model, $updateFields)
    {
        $tableName = $this->serviceGenerator->tableNameNotPlural($model['name']);

        $nameLangs = ['table'];
        $langs = config('generator.not_delete.laravel.lang');
        foreach ($langs as $key => $langComment) {
            foreach ($nameLangs as $nameLang) {
                $templateDataReal = $this->serviceGenerator->getFile('lang', 'laravel', $key . '/table.php');
                $templateDataReal = $this->generateFieldsRename(
                    $tableName,
                    $updateFields['renameFields'],
                    $templateDataReal,
                );
                $templateDataReal = $this->generateFieldsDrop(
                    $tableName,
                    $updateFields['dropFields'],
                    $templateDataReal,
                );
                $templateDataReal = $this->generateFieldsUpdate(
                    $tableName,
                    $updateFields['updateFields'],
                    $templateDataReal,
                );
                $this->serviceFile->createFileReal($this->path . $key . '/' . $nameLang . '.php', $templateDataReal);
            }
        }
    }

    private function generateFieldsRename($tableName, $renameFields, $templateDataReal)
    {
        if (empty($renameFields)) {
            return $templateDataReal;
        }

        $fieldsGenerate = [];
        $template = $this->serviceGenerator->searchTemplate(
            $tableName,
            '],',
            strlen($tableName) + 6,
            -6 - strlen($tableName),
            $templateDataReal,
        );
        $templateReplace = $this->serviceGenerator->searchTemplate(
            $tableName,
            '],',
            -strlen($tableName) + 12,
            strlen($tableName) - 10,
            $templateDataReal,
        );
        if (!$template || !$templateReplace) {
            return $templateDataReal;
        }
        $arTemplate = explode(',', trim($template));
        $arRename = \Arr::pluck($renameFields, 'field_name_new.field_name');
        $arRenameOld = \Arr::pluck($renameFields, 'field_name_old.field_name');
        $fieldsGenerate[] = " '" . $tableName . "' => [";

        foreach ($renameFields as $rename) {
            foreach ($arTemplate as $tpl) {
                if (strlen($tpl) > 0) {
                    list($fieldName, $fieldNameTrans) = explode('=>', $tpl);
                    $fieldName = trim($fieldName);
                    $fieldNameTrans = trim($fieldNameTrans);
                    $fieldName = $this->serviceGenerator->trimQuotes($fieldName);
                    $fieldNameTrans = $this->serviceGenerator->trimQuotes($fieldNameTrans);
                    if ($rename['field_name_old']['field_name'] === $fieldName) {
                        $fieldsGenerate[] =
                            "'" .
                            $rename['field_name_new']['field_name'] .
                            "'" .
                            ' => ' .
                            "'" .
                            $fieldNameTrans .
                            "'" .
                            ',';
                    } else {
                        $name = "'" . $fieldName . "'" . ' => ' . "'" . $fieldNameTrans . "'" . ',';
                        if (
                            !in_array($name, $fieldsGenerate) &&
                            !in_array($fieldName, $arRename) &&
                            !in_array($fieldName, $arRenameOld)
                        ) {
                            $fieldsGenerate[] = $name;
                        }
                    }
                }
            }
        }
        $replace = implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
        $replace .= "\n\t],";
        $templateDataReal = $this->replaceTemplate($templateReplace, $replace, $templateDataReal);
        return $templateDataReal;
    }

    private function generateFieldsUpdate($tableName, $updateFields, $templateDataReal)
    {
        if (empty($updateFields)) {
            return $templateDataReal;
        }

        $fieldsGenerate = [];
        $template = $this->serviceGenerator->searchTemplate(
            $tableName,
            '],',
            strlen($tableName) + 6,
            -6 - strlen($tableName),
            $templateDataReal,
        );
        $templateReplace = $this->serviceGenerator->searchTemplate(
            $tableName,
            '],',
            -strlen($tableName) + 12,
            strlen($tableName) - 10,
            $templateDataReal,
        );
        if (!$template || !$templateReplace) {
            return $templateDataReal;
        }
        $arTemplate = explode(',', trim($template));
        $fieldsGenerate[] = " '" . $tableName . "' => [";
        foreach ($arTemplate as $tpl) {
            if (strlen($tpl) > 0) {
                list($fieldName, $fieldNameTrans) = explode('=>', $tpl);
                $fieldName = trim($fieldName);
                $fieldNameTrans = trim($fieldNameTrans);
                $fieldsGenerate[] = $fieldName . ' => ' . $fieldNameTrans . ',';
            }
        }
        foreach ($updateFields as $update) {
            $name = "'" . $update['field_name'] . "'" . ' => ' . "'" . $update['field_name_trans'] . "',";
            $fieldsGenerate[] = $name;
        }
        $replace = implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
        $replace .= "\n\t],";
        $templateDataReal = $this->replaceTemplate($templateReplace, $replace, $templateDataReal);
        return $templateDataReal;
    }

    private function generateFieldsDrop($tableName, $dropUpdate, $templateDataReal)
    {
        if (empty($dropUpdate)) {
            return $templateDataReal;
        }

        $fieldsGenerate = [];
        $template = $this->serviceGenerator->searchTemplate(
            $tableName,
            '],',
            strlen($tableName) + 6,
            -6 - strlen($tableName),
            $templateDataReal,
        );
        $templateReplace = $this->serviceGenerator->searchTemplate(
            $tableName,
            '],',
            -strlen($tableName) + 12,
            strlen($tableName) - 10,
            $templateDataReal,
        );
        if (!$template || !$templateReplace) {
            return $templateDataReal;
        }
        $arTemplate = explode(',', trim($template));
        $dropUpdate = \Arr::pluck($dropUpdate, 'field_name');
        $fieldsGenerate[] = " '" . $tableName . "' => [";
        foreach ($arTemplate as $tpl) {
            if (strlen($tpl) > 0) {
                list($fieldName, $fieldNameTrans) = explode('=>', $tpl);
                $fieldName = trim($fieldName);
                $fieldNameTrans = trim($fieldNameTrans);
                $fieldName = $this->serviceGenerator->trimQuotes($fieldName);
                $fieldNameTrans = $this->serviceGenerator->trimQuotes($fieldNameTrans);
                $name = "'" . $fieldName . "'" . ' => ' . "'" . $fieldNameTrans . "'" . ',';
                if (!in_array($fieldName, $dropUpdate) && !in_array($name, $fieldsGenerate)) {
                    $fieldsGenerate[] = $name;
                }
            }
        }

        $replace = implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
        $replace .= "\n\t],";
        $templateDataReal = $this->replaceTemplate($templateReplace, $replace, $templateDataReal);
        return $templateDataReal;
    }

    private function replaceTemplate($template, $replace, $templateDataReal)
    {
        return str_replace($template, $replace, $templateDataReal);
    }
}
