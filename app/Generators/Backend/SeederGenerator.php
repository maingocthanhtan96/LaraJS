<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use Carbon\Carbon;

class SeederGenerator extends BaseGenerator
{
    public function __construct($fields, $model)
    {
        parent::__construct();
        $this->path = config('generator.path.laravel.seeder');

        $this->generate($fields, $model);
    }

    /**
     * @param $fields
     * @return string
     */
    public function generateFields($fields): string
    {
        $fieldsGenerate = [];
        $dbType = config('generator.db_type');

        foreach ($fields as $index => $field) {
            if ($index > 0) {
                switch ($field['db_type']) {
                    case $dbType['integer']:
                    case $dbType['bigInteger']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->numberBetween(1000, 9000),';
                        break;
                    case $dbType['float']:
                    case $dbType['double']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->randomFloat(2, 1000, 9000),';
                        break;
                    case $dbType['boolean']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->numberBetween(0, 1),';
                        break;
                    case $dbType['date']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->date("Y-m-d"),';
                        break;
                    case $dbType['dateTime']:
                    case $dbType['timestamp']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->dateTime,';
                        break;
                    case $dbType['time']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->date("H:i:s"),';
                        break;
                    case $dbType['year']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->year,';
                        break;
                    case $dbType['string']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->name,';
                        break;
                    case $dbType['text']:
                    case $dbType['longtext']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->paragraph,';
                        break;
                    case $dbType['enum']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => $faker->randomElement(' . json_encode($field['enum']) . '),';
                        break;
                    case $dbType['json']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => "[{}],"';
                        break;
                    case $dbType['file']:
                        $fieldsGenerate[] = "'" . $field['field_name'] . "'" . ' => json_encode(["https://via.placeholder.com/350"]),';
                        break;
                }
            }
        }
        return implode($this->serviceGenerator->infy_nl_tab(1, 4), $fieldsGenerate);
    }

    /**
     * @param $fields
     * @param $model
     * @return void
     */
    private function generate($fields, $model): void
    {
        $now = Carbon::now();
        $pathTemplate = 'Databases/Seeds/';
        $templateData = $this->serviceGenerator->get_template('seeder', $pathTemplate);
        $templateData = str_replace('{{DATE_TIME}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{TABLE_NAME_TITLE}}', $model['name'], $templateData);
        $templateData = str_replace('{{MODEL_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{FIELDS}}', $this->generateFields($fields), $templateData);
        if ($this->serviceGenerator->getOptions(config('generator.model.options.user_signature'), $model['options'])) {
            $templateData = $this->_generateUserSignature($templateData);
        }
        $fileName = $model['name'] . 'TableSeeder.php';

        $this->serviceFile->createFile($this->path, $fileName, $templateData);
    }

    /**
     * @param $templateData
     * @return string
     */
    private function _generateUserSignature($templateData): string
    {
        $userSignature = ['created_by', 'updated_by'];
        $notDelete = config('generator.not_delete.laravel.db');

        $fakerCreate = '$faker = \Faker\Factory::create();';
        $param = '$users';
        $fieldRelationship = $param . " = \App\Models\User::all()->pluck('id')->toArray();";
        $templateData = str_replace($fakerCreate, $fakerCreate . $this->serviceGenerator->infy_nl_tab(1, 2) . $fieldRelationship, $templateData);
        foreach ($userSignature as $signature) {
            $templateData = $this->serviceGenerator->replaceNotDelete($notDelete['seeder'], "'" . $signature . "' => " . '$faker->randomElement(' . $param . '),', 4, $templateData);
        }

        return $templateData;
    }
}
