<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class SwaggerRelationshipGenerator extends BaseGenerator
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
    public $relationship;

    CONST DB_TYPE_INTEGER = 'integer';
    CONST DB_TYPE_NUMBER = 'number';
    CONST DB_TYPE_BOOLEAN = 'boolean';
    CONST DB_TYPE_STRING = 'string';

    public function __construct($relationship, $model, $modelCurrent)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.swagger');
        $this->notDelete = config('generator.not_delete.laravel.swagger');
        $this->relationship = config('generator.relationship.relationship');

        $this->generate($relationship, $model, $modelCurrent);
    }

    private function generate($relationship, $model, $modelCurrent)
    {
        $fileName = $relationship['name'] . '.php';
        $templateData = $this->serviceGenerator->getFile('swagger', 'laravel', $fileName);
        // Model Relationship
        if ($relationship === $this->relationship['has_one'] || $relationship === $this->relationship['has_many']) {
            $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateField(\Str::snake($model) . '_id'), 1, $templateData);
        } else {
            $templateData = $this->serviceGenerator->get_template("swagger", 'Swagger/');
            $templateData = str_replace('{{DATE}}', Carbon::now()->toDateTimeString(), $templateData);
            $templateData = str_replace('{{MODEL_CLASS}}', $model['name'], $templateData);
            $templateData = str_replace('{{RESOURCE}}', $this->serviceGenerator->urlResource($model['name']), $templateData);
            $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateField(\Str::snake($model) . '_id'), 1, $templateData);
            $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateField(\Str::snake($modelCurrent) . '_id'), 1, $templateData);

            $fileName = $model . $modelCurrent . '.php';
            $this->serviceFile->createFile($this->path, $fileName, $templateData);
            return $templateData;
        }

        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateData);

        return $templateData;
    }

    private function generateField($field)
    {
        $templateProperty = $this->serviceGenerator->get_template("property", 'Swagger/');
        $templateProperty = str_replace('{{FIELD}}', $field, $templateProperty);
        $templateProperty = str_replace('{{FIELD_TRANS}}', $field, $templateProperty);
        $templateProperty = str_replace('{{DEFAULT_VALUE}}', 'NONE', $templateProperty);
        $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_INTEGER, $templateProperty);
        $templateProperty = str_replace('{{EXAMPLE}}', 1, $templateProperty);

        return $templateProperty;
    }
}
