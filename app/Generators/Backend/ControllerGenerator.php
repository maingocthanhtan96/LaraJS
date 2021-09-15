<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use Carbon\Carbon;

class ControllerGenerator extends BaseGenerator
{
    /**
     * ControllerGenerator constructor.
     * @param $fields
     * @param $model
     */
    public function __construct($fields, $model)
    {
        parent::__construct();
        $this->path = config('generator.path.laravel.api_controller');

        $this->generate($fields, $model);
    }

    /**
     * @param $fields
     * @param $model
     */
    private function generate($fields, $model): void
    {
        $now = Carbon::now();
        $pathTemplate = 'Controllers/';
        $templateData = $this->serviceGenerator->get_template('controller', $pathTemplate);
        $templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{CONTROLLER_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{MODAL_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{LIMIT}}', $model['limit'], $templateData);
        $templateData = str_replace('{{COLUMN_SEARCH}}', '[' . $this->generateColumnSearch($fields) . ']', $templateData);
        $templateData = str_replace('{{COLUMN_RELATIONSHIP}}', '[]', $templateData);
        $templateData = str_replace('{{MODAL_CLASS_PARAM}}', \Str::camel(\Str::singular($model['name'])), $templateData);

        $fileName = $model['name'] . 'Controller.php';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);
    }

    /**
     * @param $fields
     * @return string
     */
    private function generateColumnSearch($fields): string
    {
        $column = [];
        foreach ($fields as $field) {
            if ($field['show'] && $field['search']) {
                $column[] = "'" . $field['field_name'] . "'";
            }
        }

        return implode($this->serviceGenerator->infy_nl_tab(0, 0) . ', ', $column);
    }
}
