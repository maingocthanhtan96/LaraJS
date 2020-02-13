<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
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

    const DB_TYPE_INTEGER = 'integer';
    const REF_UPPER = 'Ref';

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
        $templateData = $this->serviceGenerator->getFile(
            'swagger',
            'laravel',
            $fileName
        );
        // Model Relationship
        if (
            $relationship === $this->relationship['has_one'] ||
            $relationship === $this->relationship['has_many']
        ) {
            $templateData = $this->serviceGenerator->replaceNotDelete(
                $this->notDelete['property'],
                $this->generateField(
                    \Str::snake($model) . '_id',
                    $relationship
                ),
                1,
                $templateData
            );
        } else {
            $templateData = $this->serviceGenerator->get_template(
                "swaggerMTM",
                'Swagger/'
            );
            $templateData = str_replace(
                '{{DATE}}',
                Carbon::now()->toDateTimeString(),
                $templateData
            );
            $templateData = str_replace(
                '{{MODEL_CLASS}}',
                self::REF_UPPER . $modelCurrent . $model,
                $templateData
            );
            $templateData = str_replace(
                '{{REQUIRED_FIELD_1}}',
                \Str::snake($model) . '_id',
                $templateData
            );
            $templateData = str_replace(
                '{{REQUIRED_FIELD_2}}',
                \Str::snake($modelCurrent) . '_id',
                $templateData
            );
            $templateData = str_replace(
                '{{RELATIONSHIP}}',
                $relationship,
                $templateData
            );
            $templateData = $this->serviceGenerator->replaceNotDelete(
                $this->notDelete['property'],
                $this->generateField(
                    \Str::snake($model) . '_id',
                    $relationship
                ),
                1,
                $templateData
            );
            $templateData = $this->serviceGenerator->replaceNotDelete(
                $this->notDelete['property'],
                $this->generateField(
                    \Str::snake($modelCurrent) . '_id',
                    $relationship
                ),
                1,
                $templateData
            );

            $fileName = $model . $modelCurrent . '.php';
            $this->serviceFile->createFile(
                $this->path,
                $fileName,
                $templateData
            );
            return $templateData;
        }

        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateData);

        return $templateData;
    }

    private function generateField($field, $relationship)
    {
        $templateProperty = $this->serviceGenerator->get_template(
            "property",
            'Swagger/'
        );
        $templateProperty = str_replace('{{FIELD}}', $field, $templateProperty);
        $templateProperty = str_replace(
            '{{FIELD_TRANS}}',
            $field,
            $templateProperty
        );
        $templateProperty = str_replace(
            '{{DEFAULT_VALUE}}',
            'NONE',
            $templateProperty
        );
        $templateProperty = str_replace(
            '{{DB_TYPE}}',
            self::DB_TYPE_INTEGER,
            $templateProperty
        );
        $templateProperty = str_replace('{{EXAMPLE}}', 1, $templateProperty);
        if (
            $relationship === $this->relationship['has_one'] ||
            $relationship === $this->relationship['has_many']
        ) {
            $templateProperty = str_replace(
                'description=""',
                'description="' . $relationship . '"',
                $templateProperty
            );
        }

        return $templateProperty;
    }
}
