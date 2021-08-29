<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;

class LangGenerator extends BaseGenerator
{
    public function __construct($fields, $model)
    {
        parent::__construct();
        $this->path = config('generator.path.laravel.lang');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $pathTemplate = 'Langs/';

        $nameLangs = ['route', 'table'];
        $langs = config('generator.not_delete.laravel.lang');
        foreach ($langs as $key => $langComment) {
            foreach ($nameLangs as $nameLang) {
                $templateData = $this->serviceGenerator->get_template($key . '/' . $nameLang, $pathTemplate);
                if ($nameLang === 'table') {
                    $templateData = str_replace('{{FIELDS}}', $this->generateTableFields($fields), $templateData);
                }
                $templateData = str_replace('{{LANG_MODEL_CLASS}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $templateData);
                $templateData = str_replace('{{LANG_MODEL_TRANS_CLASS}}', $model['name_trans'], $templateData);

                $templateDataReal = $this->serviceGenerator->getFile('lang', 'laravel', $key . '/' . $nameLang . '.php');

                $templateDataReal = $this->serviceGenerator->replaceNotDelete($langComment[$nameLang], $templateData, 1, $templateDataReal);
                $this->serviceFile->createFileReal($this->path . $key . '/' . $nameLang . '.php', $templateDataReal);
            }
        }

        return $pathTemplate;
    }

    private function generateTableFields($fields)
    {
        $fieldsGenerate = [];
        foreach ($fields as $field) {
            $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => ' . "'" . $field['field_name_trans'] . "',";
        }
        return implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
    }
}
