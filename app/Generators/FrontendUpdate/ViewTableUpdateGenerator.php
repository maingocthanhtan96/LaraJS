<?php

namespace App\Generators\FrontendUpdate;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;

Class ViewTableUpdateGenerator extends BaseGenerator
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

    const SORT_COLUMN = 'sortable="custom"';
    const COLUMNS = 'columns';
    const TEMPLATE_START = '<el-table-column data-generator=';
    const TEMPLATE_END = '</el-table-column>';

    public function __construct($generator, $model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.views');
        $this->notDelete = config('generator.not_delete.vuejs.views');
        $this->dbType = config('generator.db_type');

        $this->generate($generator, $model, $updateFields);
    }

    private function generate($generator, $model, $updateFields)
    {
        $fileName = $this->serviceGenerator->modelNameNotPluralFe($model['name']) . '/index.vue';
        $templateDataReal = $this->serviceGenerator->getFile('views', 'vuejs', $fileName);
        $templateDataReal = $this->generateFieldsRename($updateFields['renameFields'], $model, $templateDataReal);
        $templateDataReal = $this->generateFieldsChange($generator, $updateFields['changeFields'], $model, $templateDataReal);
        $templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsUpdate($updateFields['updateFields'], $model, $templateDataReal);
        dd($templateDataReal);
        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
    }

    private function generateFieldsRename($renameFields, $model, $templateDataReal)
    {
        if (empty($renameFields)) {
            return $templateDataReal;
        }

        $selfTemplateEnd = self::TEMPLATE_END;
        foreach ($renameFields as $index => $rename) {
            //replace template index.view
            $selfTemplateStart = self::TEMPLATE_START;
            $selfTemplateStart .= '"' . $rename['field_name_old']['field_name'] . '"';
            $templateColumn = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
            $elColumn = $this->replaceElColumn($templateColumn, $rename, $model);
            $templateDataReal = str_replace($templateColumn, $elColumn, $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsChange($generator, $changeFields, $model, $templateDataReal)
    {
        if (empty($changeFields)) {
            return $templateDataReal;
        }

        $formFields = json_decode($generator->field, true);
        $dataOld = [];
        foreach ($formFields as $index => $field) {
            if ($index > 0) {
                $dataOld[$field['id']]['db_type'] = $field['db_type'];
                $dataOld[$field['id']]['field_name'] = $field['field_name'];
            }
        }

        $selfTemplateEnd = self::TEMPLATE_END;
        foreach ($changeFields as $index => $change) {
            //replace template index.view
            $selfTemplateStart = self::TEMPLATE_START;
            $selfTemplateStart .= '"' . $change['field_name'] . '"';
            $templateColumn = $templateColumnNew = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
            if(!$change['show']) {
                $templateColumnNew = str_replace($templateColumnNew, '', $templateColumnNew);
            }
            if(!$change['sort']) {
                $templateColumnNew = str_replace(self::SORT_COLUMN, '', $templateColumnNew);
            } else {
                if(!strpos($templateColumnNew, self::SORT_COLUMN)) {
                    $generator = self::TEMPLATE_START . '"'.$change['field_name'].'"';
                    $templateColumnNew = str_replace($generator, $generator . ' ' . self::SORT_COLUMN, $templateColumnNew);
                }
            }

            // change db_type
            if($change['db_type'] !== $dataOld[$change['id']]['db_type']) {
                // remove column
                $selfTemplateEnd = self::TEMPLATE_END;
                $selfTemplateStart = self::TEMPLATE_START;
                $selfTemplateStart .= '"' . $change['field_name'] . '"';
                $templateColumnOld = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
                $templateColumnNewDB = $this->generateHandler($change, $model);
                $templateDataReal = str_replace($templateColumnOld, $templateColumnNewDB, $templateDataReal);
            }

            $templateDataReal = str_replace($templateColumn, $templateColumnNew, $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        if (empty($dropFields)) {
            return $templateDataReal;
        }

        $selfTemplateEnd = self::TEMPLATE_END;
        foreach ($dropFields as $index => $drop) {
            //replace template index.view
            $selfTemplateStart = self::TEMPLATE_START;
            $selfTemplateStart .= '"' . $drop['field_name'] . '"';
            $templateColumn = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);

            $templateDataReal = str_replace($templateColumn, '', $templateDataReal);
        }

        return $templateDataReal;
    }

    private function generateFieldsUpdate($updateFields, $model, $templateDataReal)
    {
        foreach($updateFields as $update) {
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['templates'], $this->generateHandler($update, $model), 6, $templateDataReal, 2);
        }

        return $templateDataReal;
    }

    private function replaceElColumn($templates, $field, $model)
    {
        $dataGenerator = 'data-generator=';
        $prop = 'prop=';
        $row = 'row.';
        $fieldOld = '"'.$field['field_name_old']['field_name'].'"';
        $fieldNew = '"'.$field['field_name_new']['field_name'].'"';
        // data-generator
        $templates = str_replace($dataGenerator . $fieldOld, $dataGenerator . $fieldNew, $templates);
        // prop
        $templates = str_replace($prop . $fieldOld, $prop . $fieldNew, $templates);
        // label
        $templates = str_replace($this->serviceGenerator->tableNameNotPlural($model['name']) . '.' . $field['field_name_old']['field_name'], $this->serviceGenerator->tableNameNotPlural($model['name']) . '.' . $field['field_name_new']['field_name'], $templates);
        // row
        $templates = str_replace($row . $field['field_name_old']['field_name'], $row . $field['field_name_new']['field_name'], $templates);

        if($field['field_name_old']['db_type'] === $this->dbType['file']) {
            $templates = str_replace("'".$field['field_name_old']['field_name']."_'", "'".$field['field_name_new']['field_name']."_'", $templates);
        }

        return $templates;
    }

    private function generateHandler($field, $model)
    {
        $pathTemplate = 'Handler/';
        $templateTableColumnLongText = $this->serviceGenerator->get_template("tableColumnLongText", $pathTemplate, 'vuejs');
        $templateTableColumnUploadParse = $this->serviceGenerator->get_template("tableColumnUploadParse", $pathTemplate, 'vuejs');
        $templateTableColumnBoolean = $this->serviceGenerator->get_template("tableColumnBoolean", $pathTemplate, 'vuejs');
        $templateTableColumn = $this->serviceGenerator->get_template("tableColumn", $pathTemplate, 'vuejs');

        if($field['show']) {
            if ($field['db_type'] === $this->dbType['longtext']) {
                $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumnLongText);
                $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
            } else if ($field['db_type'] === $this->dbType['file']) {
                $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumnUploadParse);
                $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
            } else if ($field['db_type'] === $this->dbType['boolean']) {
                $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumnBoolean);
                $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
            } else {
                $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumn);
                $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
                $template = str_replace('{{$ALIGN$}}', $this->generateColumnClassesFields($field), $template);
            }

            if($field['sort']) {
                $template = str_replace('{{$SORT$}}', self::SORT_COLUMN, $template);
            } else {
                $template = str_replace('{{$SORT$}}', '', $template);
            }
        }

        return $template;
    }

    private function generateColumnClassesFields($field)
    {
        switch ($field['db_type']) {
            case 'Increments':
            case $this->dbType['integer']:
            case $this->dbType['bigInteger']:
            case $this->dbType['float']:
            case $this->dbType['double']:
            case $this->dbType['boolean']:
            case $this->dbType['date']:
            case $this->dbType['dateTime']:
            case $this->dbType['time']:
            case $this->dbType['year']:
            case $this->dbType['enum']:
            case $this->dbType['file']:
                $align = "center";
                break;
            default:
                $align = 'left';
        }

        return $align;
    }
}
