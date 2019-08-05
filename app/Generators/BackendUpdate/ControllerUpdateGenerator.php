<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class ControllerUpdateGenerator extends BaseGenerator
{
	/** @var $service */
	public $serviceGenerator;

	/** @var $service */
	public $serviceFile;

	/** @var string */
	public $path;

	const COLUMNS = '$columns';
	const COLUMNS_SEARCH = '$columnSearch';

	public function __construct($model, $updateFields)
	{
		$this->serviceGenerator = new GeneratorService();
		$this->serviceFile = new FileService();
		$this->path = config('generator.path.laravel.api_controller');

		$this->generate($model, $updateFields);
	}

	private function generate($model, $updateFields)
	{
		$fileName = $model['name'] . 'Controller.php';
		$templateDataReal = $this->serviceGenerator->getFile('api_controller', 'laravel', $fileName);
		$templateDataReal = $this->generateFieldsRename($updateFields['renameFields'], $templateDataReal);
		$templateDataReal = $this->generateFieldsChange($updateFields['changeFields'], $templateDataReal);
		$templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $templateDataReal);
		$templateDataReal = $this->generateFieldsUpdate($updateFields['updateFields'], $templateDataReal);
		$fileName = $this->path . $fileName;
		$this->serviceFile->createFileReal($fileName, $templateDataReal);
	}

	private function generateFieldsRename($renameFields, $templateDataReal)
	{
		foreach ($renameFields as $rename) {
			$templateDataReal = str_replace( '"'.$rename['field_name_old']['field_name'].'"' , '"'.$rename['field_name_new']['field_name'].'"' , $templateDataReal);
			$templateDataReal = str_replace( "'".$rename['field_name_old']['field_name']."'", '"'."'".$rename['field_name_new']['field_name']."'" , $templateDataReal);
		}
		return $templateDataReal;
	}

	private function generateFieldsDrop($dropFields, $templateDataReal)
	{
		foreach ($dropFields as $drop) {
			$name = '"'.$drop['field_name'].'",';
			$templateDataReal = $this->checkComma($name, $drop['field_name'], $templateDataReal);
		}
		return $templateDataReal;
	}

	private function generateFieldsChange($changeFields, $templateDataReal)
	{
		return $this->changeUpdateFields($changeFields, $templateDataReal);
	}

	private function checkComma($name, $drop, $templateDataReal)
	{
		if(\Str::contains($templateDataReal, "$name")) {
			$templateDataReal = str_replace('"'.$drop.'",', "", $templateDataReal);
		} else {
			$templateDataReal = str_replace('"'.$drop.'"', "", $templateDataReal);
		}

		return $templateDataReal;
	}

	private function generateFieldsUpdate($updateFields, $templateDataReal)
	{
		if(empty($updateFields)) {
			return $templateDataReal;
		}
		$templateColumns = $this->serviceGenerator->searchTemplate(self::COLUMNS, '];', strlen(self::COLUMNS) + 4, -4-strlen(self::COLUMNS), $templateDataReal);
        $templateColumnsSearch = $this->serviceGenerator->searchTemplate(self::COLUMNS_SEARCH, '];', strlen(self::COLUMNS_SEARCH) + 4 ,-4-strlen(self::COLUMNS_SEARCH), $templateDataReal);

        $comma = ',';
		$commaSearch = ',';
		$columns = '';
		$columnsSearch = '';
		if(\Str::endsWith($templateColumns, ',')) {
			$comma = '';
		}
		foreach ($updateFields as $index => $update) {
			if($update['sort']) {
                $columns .= $comma . '"'.$update['field_name'].'"';
			}
		}
		if(\Str::endsWith($templateColumnsSearch, ',')) {
			$commaSearch = '';
		}
		foreach ($updateFields as $index => $update) {
			if($update['search']) {
                $columnsSearch .= $commaSearch . '"'.$update['field_name'].'"';
			}
		}
		$selfColumns = self::COLUMNS;
		$selfColumnsSearch = self::COLUMNS_SEARCH;
		$templateDataReal = str_replace("$selfColumns = [".$templateColumns."]", "$selfColumns = [".$templateColumns.$columns."]", $templateDataReal);
		$templateDataReal = str_replace("$selfColumnsSearch = [".$templateColumnsSearch."]", "$selfColumnsSearch = [".$templateColumnsSearch.$columnsSearch."]", $templateDataReal);
		return $templateDataReal;
	}

	private function changeUpdateFields($changeFields, $templateDataReal)
	{
		if(empty($changeFields)) {
			return $templateDataReal;
		}
		$fieldsGeneratorColumn = [];
		$fieldsGeneratorColumnSearch = [];
		$templateColumns = $this->serviceGenerator->searchTemplate(self::COLUMNS, '];', strlen(self::COLUMNS) + 4, -4-strlen(self::COLUMNS), $templateDataReal);
		$arrayColumns = explode(',', $templateColumns);
		$templateColumnsSearch = $this->serviceGenerator->searchTemplate(self::COLUMNS_SEARCH, '];', strlen(self::COLUMNS_SEARCH) + 4 ,-4-strlen(self::COLUMNS_SEARCH), $templateDataReal);
		$arrayColumnsSearch = explode(',', $templateColumnsSearch);
		$arrayChange = \Arr::pluck($changeFields, 'field_name');

		foreach($changeFields as $change) {
			foreach($arrayColumns as $sort) {
				$trimSort = trim($sort, '""');
				if($change['field_name'] === $trimSort) {
					if($change['sort']) {
						$fieldsGeneratorColumn[] = $change['field_name'];
					}
				} else {
					if(!in_array($trimSort, $fieldsGeneratorColumn) && !in_array($trimSort, $arrayChange)) {
						$fieldsGeneratorColumn[] = $trimSort;
					}
				}
			}
			foreach($arrayColumnsSearch as $search) {
				$trimSort = trim($search, '""');
				if($change['field_name'] === $trimSort) {
					if($change['search']) {
						$fieldsGeneratorColumnSearch[] = $change['field_name'];
					}
				} else {
					if(!in_array($trimSort, $fieldsGeneratorColumnSearch) && !in_array($trimSort, $arrayChange)) {
						$fieldsGeneratorColumnSearch[] = $trimSort;
					}
				}
			}
		}
		$selfColumns = self::COLUMNS;
		$selfColumnsSearch = self::COLUMNS_SEARCH;
		$templateDataReal = str_replace("$selfColumns = [".$templateColumns."]", "$selfColumns = ".json_encode($fieldsGeneratorColumn), $templateDataReal);
		$templateDataReal = str_replace("$selfColumnsSearch = [".$templateColumnsSearch."]", "$selfColumnsSearch = ".json_encode($fieldsGeneratorColumnSearch), $templateDataReal);
		return $templateDataReal;
	}
}