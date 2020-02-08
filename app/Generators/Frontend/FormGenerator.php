<?php

namespace App\Generators\Frontend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

class FormGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    /** @var string */
    public $labelNameForm;

    /** @var string */
    public $propNameForm;

    /** @var string */
    public $dbType;

    public function __construct($fields, $model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.views');
        $this->dbType = config('generator.db_type');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $pathTemplate = 'Views/';
        $notDelete = config('generator.not_delete.vuejs.form');
        $templateData = $this->serviceGenerator->get_template("form", $pathTemplate, 'vuejs');
        $templateData = str_replace(
            '{{$LANG_MODEL_CLASS$}}',
            $this->serviceGenerator->tableNameNotPlural($model['name']),
            $templateData
        );
        $templateData = str_replace(
            '{{$REF_MODEL_CLASS$}}',
            $this->serviceGenerator->modelNameNotPluralFe($model['name']),
            $templateData
        );
        $templateData = $this->serviceGenerator->replaceNotDelete(
            $notDelete['item'],
            $this->generateItems($fields, $model),
            5,
            $templateData,
            2
        );
        $templateData = str_replace(
            '{{$MODEL_CLASS$}}',
            $this->serviceGenerator->modelNameNotPlural($model['name']),
            $templateData
        );
        $templateData = str_replace(
            '{{$CONST_MODEL_CLASS$}}',
            $this->serviceGenerator->modelNameNotPluralFe($model['name']),
            $templateData
        );
        $templateData = str_replace($notDelete['fields'], $this->generateFields($fields), $templateData);

        $folderName = $this->path . $this->serviceGenerator->modelNameNotPluralFe($model['name']);
        if (!is_dir($folderName)) {
            mkdir($folderName, 0755, true);
        }

        $fileName = $this->serviceGenerator->modelNameNotPluralFe($model['name']) . '/form' . '.vue';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);
    }

    private function generateItems($fields, $model)
    {
        $fieldsGenerate = [];
        $this->labelNameForm = '{{$LABEL_NAME_INPUT$}}';
        $this->propNameForm = '{{$PROP_NAME$}}';

        foreach ($fields as $index => $field) {
            $tableName = $this->serviceGenerator->tableNameNotPlural($model['name']);
            switch ($field['db_type']) {
                case $this->dbType['integer']:
                case $this->dbType['bigInteger']:
                case $this->dbType['float']:
                case $this->dbType['double']:
                    $fieldsGenerate[] = $this->generateInput('inputNumber', $tableName, $field, $index);
                    break;
                case $this->dbType['boolean']:
                    $fieldsGenerate[] = $this->generateBoolean($tableName, $field);
                    break;
                case $this->dbType['date']:
                    $fieldsGenerate[] = $this->generateDateTime('date', $tableName, $field);
                    break;
                case $this->dbType['dateTime']:
                    $fieldsGenerate[] = $this->generateDateTime('dateTime', $tableName, $field);
                    break;
                case $this->dbType['time']:
                    $fieldsGenerate[] = $this->generateDateTime('time', $tableName, $field);
                    break;
                case $this->dbType['year']:
                    $fieldsGenerate[] = $this->generateDateTime('year', $tableName, $field);
                    break;
                case $this->dbType['string']:
                    $fieldsGenerate[] = $this->generateInput(
                        'input',
                        $tableName,
                        $field,
                        $index,
                        $this->dbType['string']
                    );
                    break;
                case $this->dbType['text']:
                    $fieldsGenerate[] = $this->generateInput('textarea', $tableName, $field, $index);
                    break;
                case $this->dbType['longtext']:
                    $fieldsGenerate[] = $this->generateTinymce($tableName, $field);
                    break;
                case $this->dbType['enum']:
                    $fieldsGenerate[] = $this->generateEnum($tableName, $field);
                    break;
                case $this->dbType['json']:
                    $fieldsGenerate[] = $this->generateJson($tableName, $field);
                    break;
                case $this->dbType['file']:
                    $fieldsGenerate[] = $this->generateFile($tableName, $field);
                    break;
            }
        }
        return implode($this->serviceGenerator->infy_nl_tab(1, 5, 2), $fieldsGenerate);
    }

    private function generateFields($fields)
    {
        $fieldsGenerate = [];
        $defaultValue = config('generator.default_value');
        $dbType = config('generator.db_type');
        foreach ($fields as $index => $field) {
            $fieldName = $field['field_name'];
            $fieldForm = '';
            if (
                $field['default_value'] === $defaultValue['none'] ||
                $field['default_value'] === $defaultValue['null']
            ) {
                if ($field['db_type'] === $dbType['file']) {
                    $fieldForm = "$fieldName: []";
                } elseif ($field['db_type'] === $dbType['json']) {
                    $fieldForm = "$fieldName: '[]'";
                } else {
                    $fieldForm = "$fieldName: ''";
                }
            } elseif ($field['default_value'] === $defaultValue['as_define']) {
                $asDefine = $field['as_define'];
                $fieldForm = "$fieldName: '$asDefine'";
            }
            $fieldForm .= ',';
            $fieldsGenerate[] = $fieldForm;
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
    }

    private function generateBoolean($tableName, $field)
    {
        $formTemplate = $this->getFormTemplate('switch');
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceFormField($field, $formTemplate);
        return $formTemplate;
    }

    private function generateDateTime($fileName, $tableName, $field)
    {
        $formTemplate = $this->getFormTemplate($fileName);
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceFormField($field, $formTemplate);
        return $formTemplate;
    }

    private function generateInput($fileName, $tableName, $field, $index, $dbType = '')
    {
        $formTemplate = $this->getFormTemplate($fileName);
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceAutoFocus($index, $formTemplate);
        $formTemplate = $this->replaceFormField($field, $formTemplate);
        if ($dbType === $this->dbType['string']) {
            $formTemplate = str_replace('{{MAX_LENGTH}}', $field['length_varchar'], $formTemplate);
        }
        return $formTemplate;
    }

    private function generateTinymce($tableName, $field)
    {
        $formTemplate = $this->getFormTemplate('tinymce');
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceFormField($field, $formTemplate);

        return $formTemplate;
    }

    private function generateEnum($tableName, $field)
    {
        $formTemplate = $this->getFormTemplate('select');
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceFormField($field, $formTemplate);
        $formTemplate = str_replace(
            '{{$LIST_SELECT$}}',
            $this->serviceGenerator->modelNameNotPluralFe($field['field_name']),
            $formTemplate
        );
        $formTemplate = str_replace('{{$LABEL_OPTION$}}', 'item', $formTemplate);
        $formTemplate = str_replace('{{$VALUE_OPTION$}}', 'item', $formTemplate);

        return $formTemplate;
    }

    private function generateJson($tableName, $field)
    {
        $formTemplate = $this->getFormTemplate('json');
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceFormField($field, $formTemplate);
        $formTemplate = str_replace(
            '{{$REF_JSON$}}',
            $this->serviceGenerator->modelNameNotPluralFe($field['field_name']),
            $formTemplate
        );
        return $formTemplate;
    }

    private function generateFile($tableName, $field)
    {
        $formTemplate = $this->getFormTemplate('upload');
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceFormField($field, $formTemplate);
        $formTemplate = str_replace(
            '{{$NAME_FUNC$}}',
            $this->serviceGenerator->modelNameNotPluralFe($field['field_name']),
            $formTemplate
        );
        return $formTemplate;
    }

    private function getFormTemplate($nameForm)
    {
        $pathTemplate = 'Forms/';
        $templateData = $this->serviceGenerator->get_template($nameForm, $pathTemplate, 'vuejs');

        return $templateData;
    }

    private function checkRequired($field, $formTemplate)
    {
        $formTemplate = str_replace($this->propNameForm, 'prop="' . $field['field_name'] . '"', $formTemplate);
        return $formTemplate;
    }

    private function replaceLabelForm($tableName, $field, $formTemplate)
    {
        return str_replace(
            $this->labelNameForm,
            '$t(\'table.' . $tableName . '.' . $field['field_name'] . '\')',
            $formTemplate
        );
    }

    private function replaceAutoFocus($index, $formTemplate)
    {
        if ($index === 1) {
            $formTemplate = str_replace('{{$AUTO_FOCUS_INPUT$}}', 'autofocus', $formTemplate);
        } else {
            $formTemplate = str_replace('{{$AUTO_FOCUS_INPUT$}}', '', $formTemplate);
        }

        return $formTemplate;
    }

    private function replaceFormField($field, $formTemplate)
    {
        return str_replace('{{$FORM_FIELD$}}', $field['field_name'], $formTemplate);
    }
}
