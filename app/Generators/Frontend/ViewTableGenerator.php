<?php

namespace App\Generators\Frontend;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;

class ViewTableGenerator extends BaseGenerator
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

    public function __construct($fields, $model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.views');
        $this->dbType = config('generator.db_type');
        $this->notDelete = config('generator.not_delete.vuejs.views');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $pathTemplate = 'Views/';
        $templateData = $this->serviceGenerator->get_template('indexTable', $pathTemplate, 'vuejs');
        $templateData = str_replace('{{$CONST_MODEL_CLASS$}}', $this->serviceGenerator->modelNameNotPluralFe($model['name']), $templateData);
        $templateData = str_replace('{{$NAME_ROUTE_API$}}', $this->serviceGenerator->nameAttribute($model['name']), $templateData);
        $templateData = str_replace('{{$NAME_ROUTE$}}', $this->serviceGenerator->modelNameNotPlural($model['name']), $templateData);
        $templateData = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $templateData);
        $templateData = str_replace('{{$MODEL_CLASS$}}', $this->serviceGenerator->modelNamePlural($model['name']), $templateData);
        $templateData = str_replace('{{$LIMIT$}}', $model['limit'], $templateData);
        $templateData = str_replace('{{$API_VERSION$}}', env('API_VERSION_GENERATOR', 'v1'), $templateData);
        $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['templates'], $this->generateHandler($fields, $model), 7, $templateData, 2);
        if (!$this->serviceGenerator->getOptions(config('generator.model.options.sort_deletes'), $model['options'])) {
            $selfTemplateEnd = '</el-table-column>';
            $selfTemplateStart = '<el-table-column data-generator="updated_at"';
            $templateCreatedAt = $this->serviceGenerator->searchTemplateX(
                $selfTemplateStart,
                1,
                $selfTemplateEnd,
                -strlen($selfTemplateStart),
                strlen($selfTemplateStart) + strlen($selfTemplateEnd),
                $templateData,
            );
            $templateData = str_replace($templateCreatedAt, '', $templateData);
        }

        $folderName = $this->path . $this->serviceGenerator->folderPages($model['name']);
        if (!is_dir($folderName)) {
            mkdir($folderName, 0755, true);
        }

        $fileName = $this->serviceGenerator->folderPages($model['name']) . '/index' . '.vue';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);
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
                $align = 'center';
                break;
            default:
                $align = 'left';
        }

        return $align;
    }

    private function generateHandler($fields, $model)
    {
        $fieldsGenerate = [];
        $pathTemplate = 'Handler/';
        $templateTableColumnLongText = $this->serviceGenerator->get_template('tableColumnLongText', $pathTemplate, 'vuejs');
        $templateTableColumnUploadParse = $this->serviceGenerator->get_template('tableColumnUploadParse', $pathTemplate, 'vuejs');
        $templateTableColumnBoolean = $this->serviceGenerator->get_template('tableColumnBoolean', $pathTemplate, 'vuejs');
        $templateTableColumn = $this->serviceGenerator->get_template('tableColumn', $pathTemplate, 'vuejs');

        foreach ($fields as $index => $field) {
            if ($field['show'] && $index > 0) {
                if ($field['db_type'] === $this->dbType['longtext']) {
                    $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumnLongText);
                    $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
                } elseif ($field['db_type'] === $this->dbType['file']) {
                    $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumnUploadParse);
                    $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
                } elseif ($field['db_type'] === $this->dbType['boolean']) {
                    $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumnBoolean);
                    $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
                } else {
                    $template = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateTableColumn);
                    $template = str_replace('{{$TABLE_MODEL_CLASS$}}', $this->serviceGenerator->tableNameNotPlural($model['name']), $template);
                    $template = str_replace('{{$ALIGN$}}', $this->generateColumnClassesFields($field), $template);
                }

                if ($field['sort']) {
                    $template = str_replace('{{$SORT$}}', self::SORT_COLUMN, $template);
                } else {
                    $template = str_replace('{{$SORT$}}', '', $template);
                }
                $fieldsGenerate[] = $template;
            }
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 7, 2), $fieldsGenerate);
    }
}
