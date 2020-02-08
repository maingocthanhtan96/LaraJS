<?php

namespace App\Generators\Frontend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

class RouteGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    /** @var string */
    public $notDelete;

    public function __construct($model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.router_modules');
        $this->notDelete = config('generator.not_delete.vuejs.route');

        $this->generate($model);
    }

    private function generate($model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Router/';
        $templateData = $this->serviceGenerator->get_template(
            "route",
            $pathTemplate,
            'vuejs'
        );
        $templateData = str_replace(
            '{{$DATE$}}',
            $now->toDateTimeString(),
            $templateData
        );
        $templateData = str_replace(
            '{{$NAME_ROUTE_MODEL_CLASS$}}',
            $this->serviceGenerator->modelNameNotPluralFe($model['name']),
            $templateData
        );
        $templateData = str_replace(
            '{{$PATH_ROUTE_MODEL_CLASS$}}',
            $this->serviceGenerator->urlResource($model['name']),
            $templateData
        );
        $templateData = str_replace(
            '{{$TITLE_ROUTE_MODEL_CLASS$}}',
            $this->serviceGenerator->tableNameNotPlural($model['name']),
            $templateData
        );

        $templateDataReal = $this->serviceGenerator->getFile(
            'router',
            'vuejs',
            'index.js'
        );
        if (
            $this->serviceGenerator->getOptions(
                config('generator.model.options.role_admin'),
                $model['options']
            )
        ) {
            $templateData = str_replace(
                '{{$ADMIN_ROLE$}}',
                "roles: ['admin'],",
                $templateData
            );
        } else {
            $namePermission = \Str::snake($model['name']);
            $templateData = str_replace(
                '{{$ADMIN_ROLE$}}',
                "permissions: ['view menu $namePermission'],",
                $templateData
            );
        }
        $templateDataReal = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['async'],
            $this->serviceGenerator->modelNameNotPluralFe($model['name']) . ',',
            3,
            $templateDataReal,
            2
        );
        $nameModel = $this->serviceGenerator->modelNameNotPluralFe(
            $model['name']
        );
        $templateDataReal = $this->serviceGenerator->replaceNotDelete(
            $this->notDelete['import'],
            "import $nameModel from './modules/$nameModel';",
            0,
            $templateDataReal
        );
        $fileName =
            $this->serviceGenerator->modelNameNotPluralFe($model['name']) .
            '.js';
        $this->serviceFile->createFile($this->path, $fileName, $templateData);
        $pathReal = config('generator.path.vuejs.router') . 'index.js';
        $this->serviceFile->createFileReal($pathReal, $templateDataReal);
    }
}
