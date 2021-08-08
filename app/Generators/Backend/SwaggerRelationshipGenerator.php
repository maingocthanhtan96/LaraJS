<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;
use Carbon\Carbon;

class SwaggerRelationshipGenerator extends BaseGenerator
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

    const DB_TYPE_INTEGER = 'BIGINT';
    const REF_UPPER = 'Ref';
    const OA_SCHEME = '@OA\Schema(';
    const REQUIRED = 'required={';

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
        $fileName = $model . '.php';
        $templateData = $this->serviceGenerator->getFile('swagger', 'laravel', $fileName);
        // Model Relationship
        if ($relationship === $this->relationship['has_one'] || $relationship === $this->relationship['has_many']) {
            // Required
            $templateScheme = $this->serviceGenerator->searchTemplate(self::OA_SCHEME, ')', -strlen(self::OA_SCHEME) + 4, strlen(self::OA_SCHEME) + 2, $templateData);
            $templateRequired = self::REQUIRED . $this->serviceGenerator->searchTemplate(self::REQUIRED, '}', strlen(self::REQUIRED), -strlen(self::REQUIRED), $templateScheme);
            if (!$templateScheme || !$templateRequired) {
                return $templateData;
            }
            $fieldRequires = '"' . \Str::snake($modelCurrent) . '_id' . '"';
            $templateRequiredNew = $templateRequired . ', ' . $fieldRequires;
            $templateData = str_replace($templateRequired, rtrim($templateRequiredNew, ', '), $templateData);
            // end required
            $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateField($modelCurrent, $relationship), 1, $templateData);
            $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['json_content'], $this->generateField($modelCurrent, $relationship, true), 0, $templateData);
        } else {
            $templateData = $this->serviceGenerator->get_template('swaggerMTM', 'Swagger/');
            $templateData = str_replace('{{DATE}}', Carbon::now()->toDateTimeString(), $templateData);
            $templateData = str_replace('{{MODEL_CLASS}}', self::REF_UPPER . $modelCurrent . $model, $templateData);
            $templateData = str_replace('{{REQUIRED_FIELD_1}}', \Str::snake($model) . '_id', $templateData);
            $templateData = str_replace('{{REQUIRED_FIELD_2}}', \Str::snake($modelCurrent) . '_id', $templateData);
            $templateData = str_replace('{{RELATIONSHIP}}', $relationship, $templateData);
            $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateField($model, $relationship), 1, $templateData);
            $templateData = $this->serviceGenerator->replaceNotDelete($this->notDelete['property'], $this->generateField($modelCurrent, $relationship), 1, $templateData);

            $fileName = self::REF_UPPER . $modelCurrent . $model . '.php';
            $this->serviceFile->createFile($this->path, $fileName, $templateData);
            return $templateData;
        }

        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateData);

        return $templateData;
    }

    private function generateField($modelRelationship, $relationship, $isPropertyJson = false)
    {
        if ($isPropertyJson) {
            $templateProperty = $this->serviceGenerator->get_template('propertyJson', 'Swagger/');
            $templateProperty = str_replace('{{EXAMPLE}}', 1, $templateProperty);
            $templateProperty = str_replace('{{DB_TYPE}}', self::DB_TYPE_INTEGER, $templateProperty);
        } else {
            $templateProperty = $this->serviceGenerator->get_template('propertyRelationship', 'Swagger/');
            $templateProperty = str_replace('{{MODEL_RELATIONSHIP}}', $modelRelationship, $templateProperty);
        }
        $templateProperty = str_replace('{{FIELD}}', \Str::snake($modelRelationship) . '_id', $templateProperty);
        $templateProperty = str_replace('{{DEFAULT_VALUE}}', 'NONE', $templateProperty);
        $templateProperty = str_replace('description=""', 'description="' . $relationship . '"', $templateProperty);

        return $templateProperty;
    }
}
