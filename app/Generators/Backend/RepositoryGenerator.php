<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class  RepositoryGenerator extends BaseGenerator
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
		$this->path = config('generator.path.laravel.repositories');

		$this->generate($fields, $model);
	}

	private function generate($fields, $model)
	{
		$now = Carbon::now();
		$createFolderModel = '/' . $model['name'] . '/';
		$pathTemplate = 'Repositories/';
		//template Repository
		$templateDataRepository = $this->serviceGenerator->get_template("Repository", $pathTemplate);
		$templateDataRepository = str_replace('{{DATE}}', $now->toDateTimeString(), $templateDataRepository);
		$templateDataRepository = str_replace('{{MODEL_CLASS}}', $model['name'], $templateDataRepository);
		$fileNameRepository = $model['name'].'Repository.php';
		$this->serviceFile->createFile($this->path . $createFolderModel, $fileNameRepository, $templateDataRepository);
		//template Interface
		$templateDataInterface = $this->serviceGenerator->get_template("Interface", $pathTemplate);
		$templateDataInterface = str_replace('{{DATE}}', $now->toDateTimeString(), $templateDataInterface);
		$templateDataInterface = str_replace('{{MODEL_CLASS}}', $model['name'], $templateDataInterface);
		$fileNameInterFace = $model['name'].'Interface.php';
		$this->serviceFile->createFile($this->path . $createFolderModel, $fileNameInterFace, $templateDataInterface);

		return $templateDataRepository;
	}
}