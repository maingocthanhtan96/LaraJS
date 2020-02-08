<?php

namespace App\Generators\Backend;

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

    public function __construct($model)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.laravel.api_routes');

        $this->generate($model);
    }

    private function generate($model)
    {
        $now = Carbon::now();
        $pathTemplate = 'Routes/';
        $templateData = $this->serviceGenerator->get_template("api", $pathTemplate);
        $templateData = str_replace('{{MODEL_CLASS}}', $model['name'], $templateData);
        $templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
        $templateData = str_replace(
            '{{RESOURCE}}',
            $this->serviceGenerator->urlResource($model['name']),
            $templateData
        );

        $notDelete = config('generator.not_delete.laravel.route.api');
        $templateDataReal = $this->serviceGenerator->getFile('api_routes', 'laravel');
        if ($this->serviceGenerator->getOptions(config('generator.model.options.role_admin'), $model['options'])) {
            $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                $notDelete['admin'],
                $templateData,
                4,
                $templateDataReal
            );
        } else {
            $templateDataReal = $this->serviceGenerator->replaceNotDelete(
                $notDelete['user'],
                $templateData,
                3,
                $templateDataReal
            );
        }

        $this->serviceFile->createFileReal($this->path, $templateDataReal);
    }
}
