<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

class RequestGenerator extends BaseGenerator
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
        $this->path = config('generator.path.laravel.request');
        $this->notDelete = config('generator.not_delete.laravel.request');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Requests/';
        $templateData = $this->serviceGenerator->get_template("store", $pathTemplate);
        $templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{MODEL_CLASS}}', $model['name'], $templateData);
        $templateData = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['rule'],
            $this->generateFields($fields),
            3,
            $templateData
        );

        //create sort delete
        $fileName = 'Store' . $model['name'] . 'Request.php';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);

        return $templateData;
    }

    private function generateFields($fields)
    {
        $fieldsGenerate = [];
        $dbType = config('generator.db_type');
        $configDefaultValue = config('generator.default_value');

        foreach ($fields as $index => $field) {
            if ($index > 0) {
                if ($field['default_value'] === $configDefaultValue['none']) {
                    $required = 'required';
                } else {
                    $required = 'nullable';
                }
                switch ($field['db_type']) {
                    case $dbType['integer']:
                    case $dbType['bigInteger']:
                    case $dbType['float']:
                    case $dbType['double']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => ' . "'$required|numeric'" . ',';
                        break;
                    case $dbType['boolean']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => ' . "'$required|boolean'" . ',';
                        break;
                    case $dbType['date']:
                        $fieldsGenerate[] =
                            "'" . $field['field_name'] . "'" . ' => ' . "'$required|date_format:Y-m-d'" . ',';
                        break;
                    case $dbType['dateTime']:
                        $fieldsGenerate[] =
                            "'" . $field['field_name'] . "'" . ' => ' . "'$required|date_format:Y-m-d H:i:s'" . ',';
                        break;
                    case $dbType['time']:
                        $fieldsGenerate[] =
                            "'" . $field['field_name'] . "'" . ' => ' . "'$required|date_format:H:i:s'" . ',';
                        break;
                    case $dbType['year']:
                        $fieldsGenerate[] =
                            "'" . $field['field_name'] . "'" . ' => ' . "'$required|date_format:Y'" . ',';
                        break;
                    case $dbType['string']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            "'$required|string|max:{$field['length_varchar']}'" .
                            ',';
                        break;
                    case $dbType['text']:
                    case $dbType['longtext']:
                    case $dbType['file']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => ' . "'$required|string'" . ',';
                        break;
                    case $dbType['enum']:
                        $enum = 'in:';
                        foreach ($field['enum'] as $keyEnum => $value) {
                            if ($keyEnum === count($field['enum']) - 1) {
                                $enum .= "$value";
                            } else {
                                $enum .= "$value" . ',';
                            }
                        }
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => ' . "'$required|$enum'" . ',';
                        break;
                    case $dbType['json']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => ' . "'$required|json'" . ',';
                        break;
                }
            }
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
    }
}
