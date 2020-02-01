<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class  MigrationGenerator extends BaseGenerator
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
        $this->path = config('generator.path.laravel.migration');

        $this->generate($fields, $model);
    }

    private function generate($fields, $model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Databases/Migrations/';
        $templateData = $this->serviceGenerator->get_template("migration", $pathTemplate);
        $templateData = str_replace('{{FIELDS}}', $this->generateFields($fields, $model), $templateData);
        $templateData = str_replace('{{DATE_TIME}}', $now->toDateTimeString(), $templateData);

        $templateData = str_replace('{{TABLE_NAME_TITLE}}', $this->serviceGenerator->modelNamePlural($model['name']), $templateData);
        $templateData = str_replace('{{TABLE_NAME}}', $this->serviceGenerator->tableName($model['name']), $templateData);
        $fileName = date('Y_m_d_His') . '_' . 'create_' . $this->serviceGenerator->tableName($model['name']) . '_table.php';

        $this->serviceFile->createFile($this->path, $fileName, $templateData);
    }

    private function generateFields($fields, $model)
    {
        $fieldsGenerate = [];

        $configDBType = config('generator.db_type');
        $configDefaultValue = config('generator.default_value');
        $fieldsGenerate[] = '$table->bigIncrements("id");';
        foreach ($fields as $index => $field) {
            $table = '';
            foreach ($configDBType as $typeLaravel => $typeDB) {
                if($field['db_type'] === $configDBType['string']) {
                    $table .= '$table->string("' . trim($field['field_name']) . '", '.$field['length_varchar'].')';
                    break;
                }

                if ($field['db_type'] === $configDBType['enum']) {
                    $enum = '';
                    foreach ($field['enum'] as $keyEnum => $value) {
                        if ($keyEnum === count($field['enum']) - 1) {
                            $enum .= "'$value'";
                        } else {
                            $enum .= "'$value'" . ',';
                        }
                    }
                    $table .= '$table->enum("' . trim($field['field_name']) . '", [' . $enum . '])';
                    break;
                }

                if ($field['db_type'] === $configDBType['file']) {
                    $table .= '$table->text("' . trim($field['field_name']) . '")';
                    break;
                }

                if ($field['db_type'] === $typeDB) {
                    $table .= '$table->' . $typeLaravel . '("' . trim($field['field_name']) . '")';
                    break;
                }
            }

            if ($field['default_value'] === $configDefaultValue['null']) {
                $table .= '->nullable()';
            } else if ($field['default_value'] === $configDefaultValue['as_define']) {
                $table .= '->nullable()->default("' . $field['as_define'] . '")';
            }
            if ($index > 0) {
                $table .= ';';
                $fieldsGenerate[] = $table;
            }
        }
        $fieldsGenerate[] = '$table->timestamps();';
        if ($this->serviceGenerator->getOptions(config('generator.model.options.sort_deletes'), $model['options'])) {
            $fieldsGenerate[] = '$table->softDeletes();';
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
    }
}
