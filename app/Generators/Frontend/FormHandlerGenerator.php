<?php

namespace App\Generators\Frontend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;

class FormHandlerGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    /** @var string */
    public $notDelete;

    public function __construct($fields, $model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.views');
        $this->notDelete = config('generator.not_delete.vuejs.form');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $dbType = config('generator.db_type');
        $defaultValue = config('generator.default_value');
        $folderName = $this->serviceGenerator->modelNameNotPluralFe($model['name']);
        $fileNameReal = "views/$folderName/form.vue";
        $templateDataReal = $this->serviceGenerator->getFileReal($fileNameReal, 'vuejs');
        $flags = [
            'long_text' => true,
            'json' => true,
            'upload' => true,
        ];
        foreach ($fields as $index => $field) {
            if ($index > 0) {
                if ($field['default_value'] === $defaultValue['none']) {
                    $templateRules = $this->getHandlerTemplate('rules');
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['rules'], $templateRules, 4, $templateDataReal, 2);
                    $templateDataReal = $this->replaceField($field, $model, $templateDataReal);
                }
                if ($field['db_type'] === $dbType['file']) {
                    $templateUpload = $this->getHandlerTemplate('upload');
                    $templateUpload = str_replace('{{$FIELD$}}', $field['field_name'], $templateUpload);
                    $templateUpload = str_replace('{{$NAME$}}', $this->serviceGenerator->modelNameNotPluralFe($field['field_name']), $templateUpload);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['methods'], $templateUpload, 2, $templateDataReal, 2);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['data'], $this->serviceGenerator->modelNameNotPluralFe($field['field_name']) . 'Temp: [],', 3, $templateDataReal, 2);
                    $templateStringify = $this->getHandlerTemplate('uploadStringify');
                    $templateStringify = str_replace('{{$FIELD$}}', $field['field_name'], $templateStringify);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['stringify'], $templateStringify, 3, $templateDataReal, 2);
                    // create reset field
                    $templateResetFields = $this->getHandlerTemplate('resetFile');
                    $templateResetFields = str_replace('{{$FIELD_NAME$}}', $this->serviceGenerator->modelNameNotPluralFe($field['field_name']), $templateResetFields);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['reset_field'], $templateResetFields, 5, $templateDataReal, 2);
                }
                if ($field['db_type'] === $dbType['enum']) {
                    $enum = '';
                    foreach ($field['enum'] as $keyEnum => $value) {
                        if ($keyEnum === count($field['enum']) - 1) {
                            $enum .= "'$value'";
                        } else {
                            $enum .= "'$value'" . ',';
                        }
                    }
                    $name = $field['field_name'] . "List: [" . $enum . "],";
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['data'], $name, 3, $templateDataReal, 2);
                }
                // START - IMPORT FILE
                $importVuejs = config('generator.import.vuejs');
                if ($field['db_type'] === $dbType['longtext'] && $flags['long_text']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['import_component'], $importVuejs['tinymce']['file'], 0, $templateDataReal, 2);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['import_component_name'], $importVuejs['tinymce']['name'], 2, $templateDataReal, 2);
                    $flags['long_text'] = false;
                } else if ($field['db_type'] === $dbType['json'] && $flags['json']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['import_component'], $importVuejs['json_editor']['file'], 0, $templateDataReal, 2);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['import_component_name'], $importVuejs['json_editor']['name'], 2, $templateDataReal, 2);
                    $flags['json'] = false;
                } else if ($field['db_type'] === $dbType['file'] && $flags['upload']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['import_component'], $importVuejs['vue_dropzone']['file'], 0, $templateDataReal, 2);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['import_component_name'], $importVuejs['vue_dropzone']['name'], 2, $templateDataReal, 2);
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['import_component'], $importVuejs['vue_dropzone']['request'], 0, $templateDataReal, 2);
                    $flags['upload'] = false;
                }
                // END - IMPORT FILE
            }
        }
        $pathReal = config('generator.path.vuejs.resource_js') . $fileNameReal;
        $this->serviceFile->createFileReal($pathReal, $templateDataReal);
    }

    private function getHandlerTemplate($nameForm)
    {
        $pathTemplate = 'Handler/';
        $templateData = $this->serviceGenerator->get_template($nameForm, $pathTemplate, 'vuejs');

        return $templateData;
    }

    private function replaceField($field, $model, $formTemplate)
    {
        $attribute = 'this.$t(\'table.' . $this->serviceGenerator->tableNameNotPlural($model['name']) . '.' . $field['field_name'] . "')";
        $formTemplate = str_replace('{{$ATTRIBUTE_FIELD$}}', $attribute, $formTemplate);
        return str_replace('{{$FIELD$}}', $field['field_name'], $formTemplate);
    }
}
