<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class SeederUpdateGenerator extends BaseGenerator
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

    public function __construct($generator, $model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.seeder');
        $this->notDelete = config('generator.not_delete.laravel.db');
        $this->dbType = config('generator.db_type');

        $this->generate($generator, $model, $updateFields);
    }

    private function generate($generator, $model, $updateFields)
    {
        $fileName = $model['name'] . 'TableSeeder.php';
        $templateDataReal = $this->serviceGenerator->getFile('seeder', 'laravel', $fileName);
        $templateDataReal = $this->generateRenameFields($updateFields['renameFields'], $templateDataReal);
        $templateDataReal = $this->generateChangeFields($updateFields['changeFields'], $generator, $templateDataReal);
        $templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $templateDataReal);
        if (!empty($updateFields['updateFields'])) {
            $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['seeder'], $this->generateFieldsUpdate($updateFields['updateFields'], $templateDataReal), 4, $templateDataReal);
        }
        $this->serviceFile->createFileReal($this->path . $fileName, $templateDataReal);
    }

    private function generateFields($fields)
    {
        $fieldsGenerate = [];
        foreach ($fields as $index => $field) {
            $fieldsGenerate[] = $this->switchDbType($field);
        }
        return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
    }

    private function generateRenameFields($renameFields, $templateDataReal)
    {
        foreach ($renameFields as $reanme) {
            $templateDataReal = str_replace("'" . $reanme['field_name_old']['field_name'] . "'", "'" . $reanme['field_name_new']['field_name'] . "'", $templateDataReal);
        }
        return $templateDataReal;
    }

    private function generateChangeFields($changeFields, $generator, $templateDataReal)
    {
        $formFields = json_decode($generator->field, true);
        foreach ($changeFields as $change) {
            foreach ($formFields as $index => $oldField) {
                if ($index > 0 && $oldField['id'] === $change['id']) {
                    $templateDataReal = str_replace($this->switchDbType($oldField), $this->switchDbType($change), $templateDataReal);
                }
            }
        }
        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        foreach ($dropFields as $drop) {
            $templateDataReal = str_replace($this->switchDbType($drop), '', $templateDataReal);
        }
        return $templateDataReal;
    }

    private function generateFieldsUpdate($updateFields, $templateDataReal)
    {
        $fieldsGenerate = [];
        foreach ($updateFields as $update) {
            $fieldsGenerate[] = $this->switchDbType($update);
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 4), $fieldsGenerate);
    }

    private function switchDbType($change)
    {
        $fieldsGenerate = '';
        switch ($change['db_type']) {
            case $this->dbType['integer']:
            case $this->dbType['bigInteger']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->numberBetween(1000, 9000)' . ',';
                break;
            case $this->dbType['float']:
            case $this->dbType['double']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->randomFloat(2, 1000, 9000)' . ',';
                break;
            case $this->dbType['boolean']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->numberBetween(0, 1)' . ',';
                break;
            case $this->dbType['date']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->date("Y-m-d")' . ',';
                break;
            case $this->dbType['dateTime']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->dateTime' . ',';
                break;
            case $this->dbType['time']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->date("H:i:s")' . ',';
                break;
            case $this->dbType['year']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->year' . ',';
                break;
            case $this->dbType['string']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->name' . ',';
                break;
            case $this->dbType['text']:
            case $this->dbType['longtext']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->paragraph' . ',';
                break;
            case $this->dbType['enum']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . '$faker->randomElement(' . json_encode($change['enum']) . ')' . ',';
                break;
            case $this->dbType['json']:
                $json = '{"menu": {"id": "file","value": "File","popup": {"menuitem": [{"value": "New", "onclick": "CreateNewDoc()"},{"value": "Open", "onclick":"OpenDoc()"},{"value": "Close", "onclick": "CloseDoc()"}]}}';
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . "'" . $json . "'" . ',';
                break;
            case $this->dbType['file']:
                $fieldsGenerate = "'" . $change['field_name'] . "'" . ' => ' . 'json_encode(["https://via.placeholder.com/350"])' . ',';
                break;
        }
        return $fieldsGenerate;
    }
}
