<?php

namespace App\Generators\Backend;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class ModelGenerator extends BaseGenerator
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
		$this->path = config('generator.path.laravel.model');

		$this->generate($fields, $model);
	}

	private function generate($fields, $model)
	{
		$now = Carbon::now();
		$pathTemplate = 'Models/';
		$templateData = $this->serviceGenerator->get_template("model", $pathTemplate);
		$templateData = str_replace('{{DATE}}', $now->toDateTimeString(), $templateData);
		$templateData = str_replace('{{MODEL_CLASS}}', $model['name'], $templateData);
		$templateData = str_replace('{{FIELDS}}', $this->generateFields($fields), $templateData);
		$templateData = str_replace('{{TABLE_NAME}}', $this->serviceGenerator->tableName($model['name']), $templateData);
		$templateData = str_replace('{{CATS}}', $this->generateYear($fields), $templateData);

		//create sort delete
        $importLaravel = config('generator.import.laravel.use');
        $notDelete = config('generator.not_delete.laravel.model');
        if ($this->serviceGenerator->getOptions(config('generator.model.options.sort_deletes'), $model['options'])) {
			$templateData = str_replace($notDelete['use_class'], $importLaravel['file'], $templateData);
			$templateData = str_replace($notDelete['use'], $importLaravel['name'], $templateData);
		} else {
            $templateData = str_replace($notDelete['use_class'], '', $templateData);
            $templateData = str_replace($notDelete['use'], '', $templateData);
        }
		$fileName = $model['name'] . '.php';
		$this->serviceFile->createFile($this->path, $fileName, $templateData);

		return $templateData;
	}

	private function generateFields($fields)
	{
		$fieldsGenerate = [];
		foreach($fields as $index => $field) {
			if($index > 0) {
				$fieldsGenerate[] = "'".$field['field_name']."'" . ',';
			}
		}

		return implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
	}

	private function generateYear($fields)
	{
		$fieldsGenerate = [];
		$dbType = config('generator.db_type');
		$pathTemplate = 'Models/';
		$templateCats = $this->serviceGenerator->get_template("cats", $pathTemplate);
		foreach($fields as $index => $field) {
			if($index > 0) {
				if($field['db_type'] === $dbType['year']) {
					$name = $field['field_name'];
					$fieldsGenerate[] = str_replace('{{FIELD}}', "'$name' => 'string',", $templateCats);
				}
			}
		}
		return implode($this->serviceGenerator->infy_nl_tab(1, 2), $fieldsGenerate);
	}
}