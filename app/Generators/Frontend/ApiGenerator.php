<?php

namespace App\Generators\Frontend;

use App\Generators\BaseGenerator;
use Carbon\Carbon;

class ApiGenerator extends BaseGenerator
{
    public function __construct($model)
    {
        parent::__construct();
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
        $templateData = str_replace('{{$API_VERSION$}}', env('API_VERSION_GENERATOR', 'v1'), $templateData);
        $templateData = str_replace('{{$MODEL_CLASS_URI$}}', $this->serviceGenerator->urlResource($model['name']), $templateData);

        $fileName = $this->serviceGenerator->folderPages($model['name']) . '.js';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);
    }
}
