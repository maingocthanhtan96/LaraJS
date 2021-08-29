<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use Carbon\Carbon;

class RepositoryGenerator extends BaseGenerator
{
    public function __construct($model)
    {
        parent::__construct();
        $this->path = config('generator.path.laravel.repositories');

        $this->generate($model);
    }

    private function generate($model)
    {
        $now = Carbon::now();
        $createFolderModel = '/' . $model['name'] . '/';
        $pathTemplate = 'Repositories/';
        //template Repository
        $templateDataRepository = $this->serviceGenerator->get_template('Repository', $pathTemplate);
        $templateDataRepository = str_replace('{{DATE}}', $now->toDateTimeString(), $templateDataRepository);
        $templateDataRepository = str_replace('{{MODEL_CLASS}}', $model['name'], $templateDataRepository);
        $fileNameRepository = $model['name'] . 'Repository.php';
        $this->serviceFile->createFile($this->path . $createFolderModel, $fileNameRepository, $templateDataRepository);
        //template Interface
        $templateDataInterface = $this->serviceGenerator->get_template('Interface', $pathTemplate);
        $templateDataInterface = str_replace('{{DATE}}', $now->toDateTimeString(), $templateDataInterface);
        $templateDataInterface = str_replace('{{MODEL_CLASS}}', $model['name'], $templateDataInterface);
        $fileNameInterFace = $model['name'] . 'Interface.php';
        $this->serviceFile->createFile($this->path . $createFolderModel, $fileNameInterFace, $templateDataInterface);

        return $templateDataRepository;
    }
}
