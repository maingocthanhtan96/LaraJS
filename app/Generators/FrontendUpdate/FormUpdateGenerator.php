<?php

namespace App\Generators\FrontendUpdate;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;
use Carbon\Carbon;

class FormUpdateGenerator extends BaseGenerator
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

    /** @var string */
    public $notDelete;

    /** @var string */
    public $defaultValue;

    const TEMPLATE_START = '<el-form-item';
    const TEMPLATE_END = '</el-form-item>';
    const DATA_GENERATOR = 'data-generator=';
    const DATA_FORM = 'form:';
    const RULES = 'rules() {';
    const SUCCESS_FILE = 'DropzoneS';
    const REMOVE_FILE = 'DropzoneR';

    public function __construct($generator, $model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.views');
        $this->dbType = config('generator.db_type');
        $this->notDelete = config('generator.not_delete.vuejs.form');
        $this->defaultValue = config('generator.default_value');

        $this->generate($generator, $model, $updateFields);
    }

    private function generate($generator, $model, $updateFields)
    {
        $fileName = $this->serviceGenerator->folderPages($model['name']) . '/Form.vue';
        $templateDataReal = $this->serviceGenerator->getFile('views', 'vuejs', $fileName);
        $templateDataReal = $this->generateFieldsRename($updateFields['renameFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $model, $templateDataReal);
        $templateDataReal = $this->generateFieldsChange(
            $generator,
            $updateFields['changeFields'],
            $model,
            $templateDataReal,
        );
        $templateDataReal = $this->generateFieldsUpdate($updateFields['updateFields'], $model, $templateDataReal);
        $templateDataReal = $this->importComponent($updateFields, $templateDataReal);
        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
    }

    private function generateFieldsRename($renameFields, $templateDataReal)
    {
        if (empty($renameFields)) {
            return $templateDataReal;
        }

        $selfTemplateEnd = self::TEMPLATE_END;
        $selfDataForm = self::DATA_FORM;
        $selfRules = self::RULES;
        $fieldsGenerateDataForm = [];
        $arrayChange = \Arr::pluck($renameFields, 'field_name_old.field_name');
        $templateDataForm = $this->serviceGenerator->searchTemplateX(
            $selfDataForm,
            1,
            $this->notDelete['this_check'],
            strlen($selfDataForm),
            -strlen($selfDataForm) - 4,
            $templateDataReal,
        );
        $templateRules = $this->serviceGenerator->searchTemplateX(
            $selfRules,
            1,
            $this->notDelete['rules'],
            strlen($selfRules) - 1,
            -strlen($selfRules) + 1,
            $templateDataReal,
        );
        $templateRulesTemp = $templateRules;
        $dataForms = explode(',', trim($templateDataForm));

        foreach ($renameFields as $index => $rename) {
            //replace template form item
            $selfTemplateStart = self::DATA_GENERATOR;
            $selfTemplateStart .= '"' . $rename['field_name_old']['field_name'] . '"';
            $templateFormItem = $this->serviceGenerator->searchTemplateX(
                $selfTemplateStart,
                1,
                $selfTemplateEnd,
                -strlen($selfTemplateStart) - strlen(self::TEMPLATE_START) * 2,
                strlen($selfTemplateStart) * 3,
                $templateDataReal,
            );
            $formItem = explode(' ', $templateFormItem);
            $fieldsGenerate = $this->templateForm($formItem, $rename);
            $templateDataReal = str_replace($templateFormItem, implode(' ', $fieldsGenerate), $templateDataReal);
            // replace form
            foreach ($dataForms as $form) {
                if (strlen($form) > 0) {
                    $form = trim($form);
                    list($keyForm, $valForm) = explode(':', $form, 2);
                    if ($rename['field_name_old']['field_name'] === $keyForm) {
                        $fieldsGenerateDataForm[] = $rename['field_name_new']['field_name'] . ':' . $valForm . ',';
                    } else {
                        $name = $keyForm . ':' . $valForm . ',';
                        if (!in_array($name, $fieldsGenerateDataForm) && !in_array($keyForm, $arrayChange)) {
                            $fieldsGenerateDataForm[] = $name;
                        }
                    }
                }
            }
            //replace file temp
            if ($rename['field_name_old']['db_type'] === $this->dbType['file']) {
                $templateDataReal = str_replace(
                    $this->serviceGenerator->modelNameNotPluralFe($rename['field_name_old']['field_name']) . 'Temp',
                    $this->serviceGenerator->modelNameNotPluralFe($rename['field_name_new']['field_name']) . 'Temp',
                    $templateDataReal,
                );
            }
            //replace rules
            if (\Str::contains($templateRules, $rename['field_name_old']['field_name'])) {
                $templateRulesTemp = str_replace(
                    $this->serviceGenerator->modelNameNotPluralFe($rename['field_name_old']['field_name']),
                    $this->serviceGenerator->modelNameNotPluralFe($rename['field_name_new']['field_name']),
                    $templateRulesTemp,
                );
            }
            //replace function upload file and remove file
            //success
            $successFile =
                $this->serviceGenerator->modelNameNotPluralFe($rename['field_name_old']['field_name']) .
                self::SUCCESS_FILE;
            $commentUploadFile = '// Upload file => Dropzone';
            $templateSuccessFile = $this->serviceGenerator->searchTemplateX(
                $successFile,
                2,
                $commentUploadFile,
                -strlen($successFile),
                strlen($successFile) + strlen($commentUploadFile),
                $templateDataReal,
            );
            $arraySuccess = explode(' ', $templateSuccessFile);
            $generateSuccess = $this->templateFile($arraySuccess, $rename, 'success');
            $templateDataReal = str_replace($templateSuccessFile, implode(' ', $generateSuccess), $templateDataReal);
            //remove
            $removeFile =
                $this->serviceGenerator->modelNameNotPluralFe($rename['field_name_old']['field_name']) .
                self::REMOVE_FILE;
            $commentRemoveFile = '// Remove file => Dropzone';
            $templateRemoveFile = $this->serviceGenerator->searchTemplateX(
                $removeFile,
                2,
                $commentRemoveFile,
                -strlen($removeFile),
                strlen($removeFile) + strlen($commentRemoveFile),
                $templateDataReal,
            );
            $arrayRemove = explode(' ', $templateRemoveFile);
            $generateRemove = $this->templateFile($arrayRemove, $rename, 'remove');
            $templateDataReal = str_replace($templateRemoveFile, implode(' ', $generateRemove), $templateDataReal);
            // replace json stringify {file}
            if ($rename['field_name_old']['db_type'] === $this->dbType['file']) {
                $templateStringify = $this->getHandlerTemplate('uploadStringify');
                $templateStringifyOld = str_replace(
                    '{{$FIELD$}}',
                    $rename['field_name_old']['field_name'],
                    $templateStringify,
                );
                $templateStringifyNew = str_replace(
                    '{{$FIELD$}}',
                    $rename['field_name_new']['field_name'],
                    $templateStringify,
                );
                $templateDataReal = str_replace($templateStringifyOld, $templateStringifyNew, $templateDataReal);
            }
            // replace this.form.{file}
            $templateDataReal = str_replace(
                'this.form.' . $rename['field_name_old']['field_name'],
                'this.form.' . $rename['field_name_new']['field_name'],
                $templateDataReal,
            );
            // replace id="{file}fileVueDropzone";
            $documentOldId = "document.getElementById('{$this->serviceGenerator->modelNameNotPluralFe(
                $rename['field_name_old']['field_name'],
            )}VueDropzone')";
            $documentNewId = "document.getElementById('{$this->serviceGenerator->modelNameNotPluralFe(
                $rename['field_name_new']['field_name'],
            )}VueDropzone')";
            $templateDataReal = str_replace($documentOldId, $documentNewId, $templateDataReal);
        }
        //form item
        if (!empty($fieldsGenerateDataForm)) {
            $templateDataReal = str_replace(
                $templateDataForm,
                $this->replaceTemplate($fieldsGenerateDataForm, 2, 3, 2, 2, 0),
                $templateDataReal,
            );
        }
        //rules
        $templateDataReal = str_replace($templateRules, $templateRulesTemp, $templateDataReal);

        return $templateDataReal;
    }

    private function generateFieldsChange($generator, $changeFields, $model, $templateDataReal)
    {
        if (empty($changeFields)) {
            return $templateDataReal;
        }

        $selfTemplateEnd = self::TEMPLATE_END;
        $selfDataForm = self::DATA_FORM;
        $arrayChange = \Arr::pluck($changeFields, 'field_name');
        $formFields = json_decode($generator->field, true);
        //form
        $templateDataForm = $this->serviceGenerator->searchTemplateX(
            $selfDataForm,
            1,
            $this->notDelete['this_check'],
            strlen($selfDataForm),
            -strlen($selfDataForm) - 4,
            $templateDataReal,
        );
        $dataForms = explode(',', trim($templateDataForm));
        $fieldsGenerateDataForm = [];

        foreach ($changeFields as $change) {
            foreach ($formFields as $index => $oldField) {
                if ($index > 0 && $change['id'] === $oldField['id']) {
                    // replace form item
                    $selfTemplateStart = self::DATA_GENERATOR;
                    $selfTemplateStart .= '"' . $change['field_name'] . '"';
                    $templateFormItem = $this->serviceGenerator->searchTemplateX(
                        $selfTemplateStart,
                        1,
                        $selfTemplateEnd,
                        -strlen($selfTemplateStart) - strlen(self::TEMPLATE_START) * 2,
                        strlen($selfTemplateStart) * 3,
                        $templateDataReal,
                    );
                    if ($change['db_type'] !== $oldField['db_type']) {
                        //replace template form item
                        $templateDataReal = str_replace(
                            $templateFormItem,
                            $this->generateItems($change, $model),
                            $templateDataReal,
                        );
                        //check if file
                        //drop file
                        if ($oldField['db_type'] === $this->dbType['file']) {
                            // drop file temp
                            $templateDataReal = str_replace(
                                $this->serviceGenerator->modelNameNotPluralFe($oldField['field_name']) . 'Temp: [],',
                                '',
                                $templateDataReal,
                            );
                            //drop file success
                            $successFile =
                                $this->serviceGenerator->modelNameNotPluralFe($oldField['field_name']) .
                                self::SUCCESS_FILE;
                            $commentUploadFile = '// Upload file => Dropzone';
                            $templateSuccessFile = $this->serviceGenerator->searchTemplateX(
                                $successFile,
                                1,
                                $commentUploadFile,
                                -strlen($successFile),
                                strlen($successFile) + strlen($commentUploadFile),
                                $templateDataReal,
                            );
                            if ($templateSuccessFile) {
                                $templateDataReal = str_replace($templateSuccessFile, '', $templateDataReal);
                            }
                            //drop file remove
                            $removeFile =
                                $this->serviceGenerator->modelNameNotPluralFe($oldField['field_name']) .
                                self::REMOVE_FILE;
                            $commentRemoveFile = '// Remove file => Dropzone';
                            $templateRemoveFile = $this->serviceGenerator->searchTemplateX(
                                $removeFile,
                                1,
                                $commentRemoveFile,
                                -strlen($removeFile),
                                strlen($removeFile) + strlen($commentRemoveFile),
                                $templateDataReal,
                            );
                            if ($templateRemoveFile) {
                                $templateDataReal = str_replace($templateRemoveFile, '', $templateDataReal);
                            }
                            //drop json stringify
                            $isArrayName = "if (Array.isArray(this.form.{$oldField['field_name']}))";
                            $commentJsonStringify = '// Array => stringify';
                            $templateStringify = $this->serviceGenerator->searchTemplateX(
                                $isArrayName,
                                1,
                                $commentJsonStringify,
                                -strlen($isArrayName),
                                strlen($isArrayName) + strlen($commentJsonStringify),
                                $templateDataReal,
                            );
                            $templateDataReal = str_replace($templateStringify, '', $templateDataReal);
                            // drop reset file
                            $forEachName = "this.{$this->serviceGenerator->modelNameNotPluralFe(
                                $oldField['field_name'],
                            )}Temp.forEach";
                            $removeClassDz = ".remove('dz-started');";
                            $searchResetField = $this->serviceGenerator->searchTemplateX(
                                $forEachName,
                                1,
                                $removeClassDz,
                                -strlen($forEachName),
                                strlen($removeClassDz) * 2,
                                $templateDataReal,
                            );
                            if ($searchResetField) {
                                $templateDataReal = str_replace($searchResetField, '', $templateDataReal);
                            }
                        }
                        // add file
                        if ($change['db_type'] === $this->dbType['file']) {
                            $templateDataReal = $this->generateFileAndHanler($change, $templateDataReal);
                        }
                    } else {
                        preg_match('/maxlength=(\'|")[0-9]{0,3}(\'|")/im', $templateFormItem, $matches);
                        if (isset($matches[0])) {
                            $templateFormItemNew = str_replace(
                                $matches[0],
                                'maxlength=' . '"' . $change['length_varchar'] . '"',
                                $templateFormItem,
                            );
                            $templateDataReal = str_replace($templateFormItem, $templateFormItemNew, $templateDataReal);
                        }
                    }
                    //replace rules

                    if ($change['default_value'] !== $oldField['default_value']) {
                        //drop rules
                        if ($oldField['default_value'] === $this->defaultValue['none']) {
                            $templateDataReal = $this->dropRules($change, $model, $templateDataReal);
                        }
                        //add rule
                        if ($change['default_value'] === $this->defaultValue['none']) {
                            $templateDataReal = $this->generateRule($change, $model, $templateDataReal);
                        }
                    }
                }
                // replace form
                foreach ($dataForms as $form) {
                    if (strlen($form) > 0) {
                        $form = trim($form);
                        list($keyForm, $valForm) = explode(':', $form, 2);
                        if ($change['field_name'] === $keyForm) {
                            if ($change['default_value'] === $this->defaultValue['as_define']) {
                                $formDefault = $change['field_name'] . ':' . "'" . $change['as_define'] . "'" . ',';
                                if (!in_array($formDefault, $fieldsGenerateDataForm)) {
                                    $fieldsGenerateDataForm[] = $formDefault;
                                }
                            } else {
                                if ($valForm === ' []' || $valForm === " '[]'" || $valForm === " ''") {
                                    if ($change['db_type'] === $this->dbType['file']) {
                                        $valForm = ' []';
                                    } elseif ($change['db_type'] === $this->dbType['json']) {
                                        $valForm = " '[]'";
                                    } else {
                                        $valForm = " ''";
                                    }
                                }

                                $formNotDefault = $change['field_name'] . ':' . $valForm . ',';
                                if (!in_array($formNotDefault, $fieldsGenerateDataForm)) {
                                    $fieldsGenerateDataForm[] = $formNotDefault;
                                }
                            }
                        } else {
                            $name = $keyForm . ':' . $valForm . ',';
                            if (!in_array($name, $fieldsGenerateDataForm) && !in_array($keyForm, $arrayChange)) {
                                $fieldsGenerateDataForm[] = $name;
                            }
                        }
                    }
                }
            }
        }

        //form item
        if (!empty($fieldsGenerateDataForm)) {
            $templateDataReal = str_replace(
                $templateDataForm,
                $this->replaceTemplate($fieldsGenerateDataForm, 2, 3, 2, 2, 0),
                $templateDataReal,
            );
        }

        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $model, $templateDataReal)
    {
        if (empty($dropFields)) {
            return $templateDataReal;
        }

        $selfTemplateEnd = self::TEMPLATE_END;
        $selfDataForm = self::DATA_FORM;
        $fieldsGenerateDataForm = [];
        $templateDataForm = $this->serviceGenerator->searchTemplateX(
            $selfDataForm,
            1,
            $this->notDelete['this_check'],
            strlen($selfDataForm),
            -strlen($selfDataForm) - 4,
            $templateDataReal,
        );
        $dataForms = explode(',', trim($templateDataForm));
        $arrayChange = \Arr::pluck($dropFields, 'field_name');
        foreach ($dropFields as $index => $drop) {
            //replace template form item
            $selfTemplateStart = self::DATA_GENERATOR;
            $selfTemplateStart .= '"' . $drop['field_name'] . '"';
            $templateFormItem = $this->serviceGenerator->searchTemplateX(
                $selfTemplateStart,
                1,
                $selfTemplateEnd,
                -strlen($selfTemplateStart) - strlen(self::TEMPLATE_START) * 2,
                strlen($selfTemplateStart) * 3,
                $templateDataReal,
            );
            $templateDataReal = str_replace($templateFormItem, '', $templateDataReal);
            // drop form
            foreach ($dataForms as $form) {
                if (strlen($form) > 0) {
                    $form = trim($form);
                    list($keyForm, $valForm) = explode(':', $form, 2);
                    if ($drop['field_name'] !== $keyForm) {
                        $name = $keyForm . ':' . $valForm . ',';
                        if (!in_array($name, $fieldsGenerateDataForm) && !in_array($keyForm, $arrayChange)) {
                            $fieldsGenerateDataForm[] = $name;
                        }
                    }
                }
            }
            //drop file
            if ($drop['db_type'] === $this->dbType['file']) {
                // drop file temp
                $templateDataReal = str_replace(
                    $this->serviceGenerator->modelNameNotPluralFe($drop['field_name']) . 'Temp: [],',
                    '',
                    $templateDataReal,
                );
                //drop file success
                $successFile = $this->serviceGenerator->modelNameNotPluralFe($drop['field_name']) . self::SUCCESS_FILE;
                $commentUploadFile = '// Upload file => Dropzone';
                $templateSuccessFile = $this->serviceGenerator->searchTemplateX(
                    $successFile,
                    1,
                    $commentUploadFile,
                    -strlen($successFile),
                    strlen($successFile) + strlen($commentUploadFile),
                    $templateDataReal,
                );
                if ($templateSuccessFile) {
                    $templateDataReal = str_replace($templateSuccessFile, '', $templateDataReal);
                }
                //drop file remove
                $removeFile = $this->serviceGenerator->modelNameNotPluralFe($drop['field_name']) . self::REMOVE_FILE;
                $commentRemoveFile = '// Remove file => Dropzone';
                $templateRemoveFile = $this->serviceGenerator->searchTemplateX(
                    $removeFile,
                    1,
                    $commentRemoveFile,
                    -strlen($removeFile),
                    strlen($removeFile) + strlen($commentRemoveFile),
                    $templateDataReal,
                );
                if ($templateRemoveFile) {
                    $templateDataReal = str_replace($templateRemoveFile, '', $templateDataReal);
                }
                //drop json stringify
                $isArrayName = "if (Array.isArray(this.form.{$drop['field_name']}))";
                $commentJsonStringify = '// Array => stringify';
                $templateStringify = $this->serviceGenerator->searchTemplateX(
                    $isArrayName,
                    1,
                    $commentJsonStringify,
                    -strlen($isArrayName),
                    strlen($isArrayName) + strlen($commentJsonStringify),
                    $templateDataReal,
                );
                $templateDataReal = str_replace($templateStringify, '', $templateDataReal);
                // drop reset file
                $forEachName = "this.{$this->serviceGenerator->modelNameNotPluralFe($drop['field_name'])}Temp.forEach";
                $removeClassDz = ".remove('dz-started');";
                $searchResetField = $this->serviceGenerator->searchTemplateX(
                    $forEachName,
                    1,
                    $removeClassDz,
                    -strlen($forEachName),
                    strlen($removeClassDz) * 2,
                    $templateDataReal,
                );
                if ($searchResetField) {
                    $templateDataReal = str_replace($searchResetField, '', $templateDataReal);
                }
            }
            //drop rules
            $templateDataReal = $this->dropRules($drop, $model, $templateDataReal);
        }
        if (!empty($fieldsGenerateDataForm)) {
            $templateDataReal = str_replace(
                $templateDataForm,
                $this->replaceTemplate($fieldsGenerateDataForm, 2, 3, 2, 2, 0),
                $templateDataReal,
            );
        }

        return $templateDataReal;
    }

    private function generateFieldsUpdate($updateFields, $model, $templateDataReal)
    {
        if (empty($updateFields)) {
            return $templateDataReal;
        }
        $selfDataForm = self::DATA_FORM;
        //create form
        $templateDataForm = $this->serviceGenerator->searchTemplateX(
            $selfDataForm,
            1,
            $this->notDelete['this_check'],
            strlen($selfDataForm),
            -strlen($selfDataForm) - 4,
            $templateDataReal,
        );
        $dataForms = explode(',', trim($templateDataForm));
        $fieldsGenerateDataForm = [];
        foreach ($dataForms as $form) {
            if (strlen($form) > 0) {
                $form = trim($form);
                list($keyForm, $valForm) = explode(':', $form, 2);
                $name = $keyForm . ':' . $valForm . ',';
                $fieldsGenerateDataForm[] = $name;
            }
        }
        if (!empty($fieldsGenerateDataForm)) {
            $fieldsGenerateDataForm = array_merge($fieldsGenerateDataForm, $this->generateFields($updateFields));
            $templateDataReal = str_replace(
                $templateDataForm,
                $this->replaceTemplate($fieldsGenerateDataForm, 2, 3, 2, 0, 0),
                $templateDataReal,
            );
        }

        foreach ($updateFields as $update) {
            //create form item
            $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                $this->notDelete['item'],
                $this->generateItems($update, $model),
                5,
                $templateDataReal,
                2,
            );
            //create rule
            if ($update['default_value'] === $this->defaultValue['none']) {
                $templateRules = $this->getHandlerTemplate('rules');
                $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                    $this->notDelete['rules'],
                    $templateRules,
                    4,
                    $templateDataReal,
                    2,
                );
                $templateDataReal = $this->replaceField($update, $model, $templateDataReal);
            }
            if ($update['db_type'] === $this->dbType['file']) {
                $templateUpload = $this->getHandlerTemplate('upload');
                $templateUpload = str_replace('{{$FIELD$}}', $update['field_name'], $templateUpload);
                $templateUpload = str_replace(
                    '{{$NAME$}}',
                    $this->serviceGenerator->modelNameNotPluralFe($update['field_name']),
                    $templateUpload,
                );
                $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                    $this->notDelete['methods'],
                    $templateUpload,
                    2,
                    $templateDataReal,
                    2,
                );
                $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                    $this->notDelete['data'],
                    $this->serviceGenerator->modelNameNotPluralFe($update['field_name']) . 'Temp: [],',
                    3,
                    $templateDataReal,
                    2,
                );
                $templateStringify = $this->getHandlerTemplate('uploadStringify');
                $templateStringify = str_replace('{{$FIELD$}}', $update['field_name'], $templateStringify);
                $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                    $this->notDelete['stringify'],
                    $templateStringify,
                    3,
                    $templateDataReal,
                    2,
                );
                // create reset field
                $templateResetFields = $this->getHandlerTemplate('resetFile');
                $templateResetFields = str_replace(
                    '{{$FIELD_NAME$}}',
                    $this->serviceGenerator->modelNameNotPluralFe($update['field_name']),
                    $templateResetFields,
                );
                $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                    $this->notDelete['reset_field'],
                    $templateResetFields,
                    5,
                    $templateDataReal,
                    2,
                );
            }
            if ($update['db_type'] === $this->dbType['enum']) {
                $enum = '';
                foreach ($update['enum'] as $keyEnum => $value) {
                    if ($keyEnum === count($update['enum']) - 1) {
                        $enum .= "'$value'";
                    } else {
                        $enum .= "'$value'" . ',';
                    }
                }
                $name = $update['field_name'] . 'List: [' . $enum . '],';
                $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                    $this->notDelete['data'],
                    $name,
                    3,
                    $templateDataReal,
                    2,
                );
            }
        }

        return $templateDataReal;
    }

    private function importComponent($updateFields, $templateDataReal)
    {
        $megerUpdate = array_merge($updateFields['changeFields'], $updateFields['updateFields']);
        $flags = [
            'import' => [
                'long_text' => true,
                'json' => true,
                'upload' => true,
            ],
            'component' => [
                'long_text' => true,
                'json' => true,
                'upload' => true,
                'removeFile' => true,
            ],
        ];
        $importVuejs = config('generator.import.vuejs');
        foreach ($megerUpdate as $field) {
            if ($field['db_type'] === $this->dbType['longtext'] && $flags['import']['long_text']) {
                if (!strpos($templateDataReal, $importVuejs['tinymce']['file'])) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                        $this->notDelete['import_component'],
                        $importVuejs['tinymce']['file'],
                        0,
                        $templateDataReal,
                        2,
                    );
                    $flags['import']['long_text'] = false;
                }
                if (!strpos($templateDataReal, $importVuejs['tinymce']['name']) && $flags['component']['long_text']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                        $this->notDelete['import_component_name'],
                        $importVuejs['tinymce']['name'],
                        2,
                        $templateDataReal,
                        2,
                    );
                    $flags['component']['long_text'] = false;
                }
            } elseif ($field['db_type'] === $this->dbType['json']) {
                if (!strpos($templateDataReal, $importVuejs['json_editor']['file']) && $flags['import']['json']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                        $this->notDelete['import_component'],
                        $importVuejs['json_editor']['file'],
                        0,
                        $templateDataReal,
                        2,
                    );
                    $flags['import']['json'] = false;
                }
                if (!strpos($templateDataReal, $importVuejs['json_editor']['name']) && $flags['component']['json']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                        $this->notDelete['import_component_name'],
                        $importVuejs['json_editor']['name'],
                        2,
                        $templateDataReal,
                        2,
                    );
                    $flags['component']['json'] = false;
                }
            } elseif ($field['db_type'] === $this->dbType['file']) {
                if (!strpos($templateDataReal, $importVuejs['vue_dropzone']['file']) && $flags['import']['upload']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                        $this->notDelete['import_component'],
                        $importVuejs['vue_dropzone']['file'],
                        0,
                        $templateDataReal,
                        2,
                    );
                    $flags['import']['upload'] = false;
                }
                if (!strpos($templateDataReal, $importVuejs['vue_dropzone']['name']) && $flags['component']['upload']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                        $this->notDelete['import_component_name'],
                        $importVuejs['vue_dropzone']['name'],
                        2,
                        $templateDataReal,
                        2,
                    );
                    $flags['component']['upload'] = false;
                }
                if (
                    !strpos($templateDataReal, $importVuejs['vue_dropzone']['request']) &&
                    $flags['component']['removeFile']
                ) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                        $this->notDelete['import_component'],
                        $importVuejs['vue_dropzone']['request'],
                        0,
                        $templateDataReal,
                        2,
                    );
                    $flags['component']['removeFile'] = false;
                }
            }
        }
        return $templateDataReal;
    }

    private function generateItems($field, $model)
    {
        $fieldsGenerate = [];
        $this->labelNameForm = '{{$LABEL_NAME_INPUT$}}';
        $this->propNameForm = '{{$PROP_NAME$}}';

        $tableName = $this->serviceGenerator->tableNameNotPlural($model['name']);
        switch ($field['db_type']) {
            case $this->dbType['integer']:
            case $this->dbType['bigInteger']:
            case $this->dbType['float']:
            case $this->dbType['double']:
                $fieldsGenerate[] = $this->generateInput('inputNumber', $tableName, $field);
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
                $fieldsGenerate[] = $this->generateInput('input', $tableName, $field, $this->dbType['string']);
                break;
            case $this->dbType['text']:
                $fieldsGenerate[] = $this->generateInput('textarea', $tableName, $field);
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
        return implode($this->serviceGenerator->infy_nl_tab(1, 3, 2), $fieldsGenerate);
    }

    private function generateFields($fields)
    {
        $fieldsGenerate = [];
        foreach ($fields as $index => $field) {
            $fieldName = $field['field_name'];
            $fieldForm = '';
            if (
                $field['default_value'] === $this->defaultValue['none'] ||
                $field['default_value'] === $this->defaultValue['null']
            ) {
                if ($field['db_type'] === $this->dbType['file']) {
                    $fieldForm = "$fieldName: []";
                } elseif ($field['db_type'] === $this->dbType['json']) {
                    $fieldForm = "$fieldName: '[]'";
                } else {
                    $fieldForm = "$fieldName: ''";
                }
            } elseif ($field['default_value'] === $this->defaultValue['as_define']) {
                $asDefine = $field['as_define'];
                if (is_numeric($asDefine)) {
                    $fieldForm = "$fieldName: $asDefine";
                } else {
                    $fieldForm = "$fieldName: '$asDefine'";
                }
            }
            $fieldForm .= ',';
            $fieldsGenerate[] = $fieldForm;
        }
        return $fieldsGenerate;
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

    private function generateInput($fileName, $tableName, $field, $dbType = '')
    {
        $formTemplate = $this->getFormTemplate($fileName);
        $formTemplate = $this->replaceLabelForm($tableName, $field, $formTemplate);
        $formTemplate = $this->checkRequired($field, $formTemplate);
        $formTemplate = $this->replaceAutoFocus($formTemplate);
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
            $formTemplate,
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
            $formTemplate,
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
            $formTemplate,
        );
        return $formTemplate;
    }

    private function generateRule($field, $model, $templateDataReal)
    {
        $templateRules = $this->getHandlerTemplate('rules');
        $templateDataReal = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['rules'],
            $templateRules,
            4,
            $templateDataReal,
            2,
        );
        $templateDataReal = $this->replaceField($field, $model, $templateDataReal);
        return $templateDataReal;
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
            $formTemplate,
        );
    }

    private function replaceField($field, $model, $formTemplate)
    {
        $attribute =
            'this.$t(\'table.' .
            $this->serviceGenerator->tableNameNotPlural($model['name']) .
            '.' .
            $field['field_name'] .
            "')";
        $formTemplate = str_replace('{{$ATTRIBUTE_FIELD$}}', $attribute, $formTemplate);
        return str_replace('{{$FIELD$}}', $field['field_name'], $formTemplate);
    }

    private function replaceFormField($field, $formTemplate)
    {
        return str_replace('{{$FORM_FIELD$}}', $field['field_name'], $formTemplate);
    }

    private function getHandlerTemplate($nameForm)
    {
        $pathTemplate = 'Handler/';
        $templateData = $this->serviceGenerator->get_template($nameForm, $pathTemplate, 'vuejs');

        return $templateData;
    }

    private function replaceAutoFocus($formTemplate)
    {
        $formTemplate = str_replace('{{$AUTO_FOCUS_INPUT$}}', '', $formTemplate);
        return $formTemplate;
    }

    private function templateForm($templates, $fields)
    {
        $fieldsGenerate = [];
        $fieldOld = $this->serviceGenerator->modelNameNotPluralFe($fields['field_name_old']['field_name']);
        $fieldNew = $this->serviceGenerator->modelNameNotPluralFe($fields['field_name_new']['field_name']);
        foreach ($templates as $template) {
            if (\Str::contains($template, $fields['field_name_old']['field_name'])) {
                $id = 'id="' . $fieldOld . 'VueDropzone"';
                $success = '@success="' . $fieldOld . 'DropzoneS"';
                $removedFile = '@removedFile="' . $fieldOld . 'DropzoneR"';
                if ($template === $id || $template === $success || $template === $removedFile) {
                    $fieldsGenerate[] = str_replace($fieldOld, $fieldNew, $template);
                    continue;
                }
                if (!\Str::contains($template, '<json-editor')) {
                    $fieldsGenerate[] = str_replace(
                        $fields['field_name_old']['field_name'],
                        $fields['field_name_new']['field_name'],
                        $template,
                    );
                } else {
                    $fieldsGenerate[] = $template;
                }
            } else {
                $fieldsGenerate[] = $template;
            }
        }

        return $fieldsGenerate;
    }

    private function templateFile($templates, $fields, $type)
    {
        $fieldsGenerate = [];
        $dropzoneS = 'DropzoneS';
        $dropzoneR = 'DropzoneR';
        $fieldOld = $this->serviceGenerator->modelNameNotPluralFe($fields['field_name_old']['field_name']);
        $fieldNew = $this->serviceGenerator->modelNameNotPluralFe($fields['field_name_new']['field_name']);
        foreach ($templates as $template) {
            if (\Str::contains($template, $fieldOld)) {
                $success = $fieldOld . $dropzoneS;
                $removedFile = $fieldOld . $dropzoneR;
                //replace function name
                if (\Str::contains($template, $success) || \Str::contains($template, $removedFile)) {
                    if ($type === 'success') {
                        $fieldsGenerate[] = str_replace($success, $fieldNew . $dropzoneS, $template);
                    } elseif ($type === 'remove') {
                        $fieldsGenerate[] = str_replace($removedFile, $fieldNew . $dropzoneR, $template);
                    }
                    continue;
                }
                //replace file temp
                //                if(!\Str::contains($template, $this->serviceGenerator->modelNameNotPluralFe(
                //                    $fields['field_name_new']['field_name']) . 'Temp')) {
                //                    $fieldsGenerate[] = str_replace($fields['field_name_old']['field_name'], $fields['field_name_new']['field_name'], $template);
                //                } else {
                $fieldsGenerate[] = $template;
                //                }
            } else {
                $fieldsGenerate[] = $template;
            }
        }
        return $fieldsGenerate;
    }

    private function replaceTemplate($fieldsGenerate, $tabStart, $tabEnd, $tabFields, $space = 2, $start = 1)
    {
        return $this->serviceGenerator->infy_nl_tab($start, $tabStart) .
            implode($this->serviceGenerator->infy_nl_tab(1, $tabFields), $fieldsGenerate) .
            $this->serviceGenerator->infy_nl_tab(1, $tabEnd, $space);
    }

    private function dropRules($field, $model, $templateDataReal)
    {
        $replaceStub = '';
        $searchRules = 'rules() {';
        $fieldName = $field['field_name'];
        $templateRules = $this->serviceGenerator->searchTemplateX(
            $searchRules,
            1,
            '};',
            strlen($searchRules) - 5,
            -strlen($searchRules) + 7,
            $templateDataReal,
        );
        preg_match_all('/]/', $templateRules, $matches, PREG_OFFSET_CAPTURE);
        if (!empty($matches[0])) {
            $positionEnd = 0;
            if (count($matches[0]) === 1) {
                $positionEnd = $matches[0][0][1];
            }
            if (count($matches[0]) === 2) {
                $positionEnd = $matches[0][1][1];
            }
            if ($positionEnd) {
                $lengStart = strpos($templateRules, "$fieldName:");
                if ($lengStart) {
                    $replaceStub = substr($templateRules, $lengStart, $positionEnd - strlen("$fieldName:") - 4);
                }
            }
        }

        if ($replaceStub) {
            $templateDataReal = str_replace($replaceStub, '', $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFileAndHanler($field, $templateDataReal)
    {
        $templateUpload = $this->getHandlerTemplate('upload');
        $templateUpload = str_replace('{{$FIELD$}}', $field['field_name'], $templateUpload);
        $templateUpload = str_replace(
            '{{$NAME$}}',
            $this->serviceGenerator->modelNameNotPluralFe($field['field_name']),
            $templateUpload,
        );
        $templateDataReal = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['methods'],
            $templateUpload,
            2,
            $templateDataReal,
            2,
        );
        $templateDataReal = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['data'],
            $this->serviceGenerator->modelNameNotPluralFe($field['field_name']) . 'Temp: [],',
            3,
            $templateDataReal,
            2,
        );
        $templateStringify = $this->getHandlerTemplate('uploadStringify');
        $templateStringify = str_replace('{{$FIELD$}}', $field['field_name'], $templateStringify);
        $templateDataReal = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['stringify'],
            $templateStringify,
            3,
            $templateDataReal,
            2,
        );
        return $templateDataReal;
    }
}
