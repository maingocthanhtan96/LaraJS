<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;
use Carbon\Carbon;

class ControllerGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    public function __construct($fields, $model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.api_controller');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Controllers/';
        $templateData = $this->serviceGenerator->get_template('controller', $pathTemplate);
        $templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{CONTROLLER_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{MODAL_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{LIMIT}}', $model['limit'], $templateData);
        $templateData = str_replace('{{COLUMN_SORT}}', '[' . $this->generateColumnSoft($fields, $model) . ']', $templateData);
        $templateData = str_replace('{{COLUMN_SEARCH}}', '[' . $this->generateColumnSearch($fields) . ']', $templateData);
        $templateData = str_replace('{{COLUMN_RELATIONSHIP}}', '[]', $templateData);
        $templateData = str_replace('{{MODAL_CLASS_PARAM}}', \Str::camel($model['name']), $templateData);

        $fileName = $model['name'] . 'Controller.php';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);

        return $templateData;
    }

    private function generateColumnSearch($fields)
    {
        $column = [];
        foreach ($fields as $field) {
            if ($field['show'] && $field['search']) {
                $column[] = "'" . $field['field_name'] . "'";
            }
        }

        return implode($this->serviceGenerator->infy_nl_tab(0, 0) . ', ', $column);
    }

    private function generateColumnSoft($fields, $model)
    {
        $column = [];
        foreach ($fields as $index => $field) {
            if ($field['show'] && $field['sort']) {
                $column[] = "'" . $field['field_name'] . "'";
            }
        }
        if ($this->serviceGenerator->getOptions(config('generator.model.options.sort_deletes'), $model['options'])) {
            $fieldsGenerate[] = "'updated_at'";
        }

        return implode($this->serviceGenerator->infy_nl_tab(0, 0) . ', ', $column);
    }
}
