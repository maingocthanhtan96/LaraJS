<?php

namespace App\Generators\Frontend;

use App\Generators\BaseGenerator;
use App\Services\FileService;
use App\Services\GeneratorService;
use Carbon\Carbon;

class ApiGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    public function __construct($model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.api');

        $this->generate($model);
    }

    private function generate($model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Api/';
        $templateData = $this->serviceGenerator->get_template('api', $pathTemplate, 'vuejs');
        $templateData = str_replace('{{$DATE$}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace('{{$MODEL_CLASS$}}', $model['name'], $templateData);
        $templateData = str_replace(
            '{{$MODEL_CLASS_URI$}}',
            $this->serviceGenerator->urlResource($model['name']),
            $templateData,
        );

        $fileName = $this->serviceGenerator->folderPages($model['name']) . '.js';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);
    }
}
