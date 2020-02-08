<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

class SeederGenerator extends BaseGenerator
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
        $this->path = config('generator.path.laravel.seeder');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Databases/Seeds/';
        $templateData = $this->serviceGenerator->get_template(
            "seeder",
            $pathTemplate
        );
        $templateData = str_replace(
            '{{DATE_TIME}}',
            $now->toDateTimeString(),
            $templateData
        );
        $templateData = str_replace(
            '{{TABLE_NAME_TITLE}}',
            $model['name'],
            $templateData
        );
        $templateData = str_replace(
            '{{MODEL_CLASS}}',
            $model['name'],
            $templateData
        );
        $templateData = str_replace(
            '{{FIELDS}}',
            $this->generateFields($fields),
            $templateData
        );
        $fileName = $model['name'] . 'TableSeeder.php';

        $this->serviceFile->createFile($this->path, $fileName, $templateData);

        return $templateData;
    }

    public function generateFields($fields)
    {
        $fieldsGenerate = [];
        $dbType = config('generator.db_type');

        foreach ($fields as $index => $field) {
            if ($index > 0) {
                switch ($field['db_type']) {
                    case $dbType['integer']:
                    case $dbType['bigInteger']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->numberBetween(1000, 9000)' .
                            ',';
                        break;
                    case $dbType['float']:
                    case $dbType['double']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->randomFloat(2, 1000, 9000)' .
                            ',';
                        break;
                    case $dbType['boolean']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->numberBetween(0, 1)' .
                            ',';
                        break;
                    case $dbType['date']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->date("Y-m-d")' .
                            ',';
                        break;
                    case $dbType['dateTime']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->dateTime' .
                            ',';
                        break;
                    case $dbType['time']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->date("H:i:s")' .
                            ',';
                        break;
                    case $dbType['year']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->year' .
                            ',';
                        break;
                    case $dbType['string']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->name' .
                            ',';
                        break;
                    case $dbType['text']:
                    case $dbType['longtext']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->paragraph' .
                            ',';
                        break;
                    case $dbType['enum']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            '$faker->randomElement(' .
                            json_encode($field['enum']) .
                            ')' .
                            ',';
                        break;
                    case $dbType['json']:
                        $json =
                            '{"menu": {"id": "file","value": "File","popup": {"menuitem": [{"value": "New", "onclick": "CreateNewDoc()"},{"value": "Open", "onclick":"OpenDoc()"},{"value": "Close", "onclick": "CloseDoc()"}]}}';
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            "'" .
                            $json .
                            "'" .
                            ',';
                        break;
                    case $dbType['file']:
                        $fieldsGenerate[] =
                            "'" .
                            $field['field_name'] .
                            "'" .
                            ' => ' .
                            'json_encode(["https://via.placeholder.com/350"])' .
                            ',';
                        break;
                }
            }
        }
        return implode(
            $this->serviceGenerator->infy_nl_tab(1, 4),
            $fieldsGenerate
        );
    }
}
