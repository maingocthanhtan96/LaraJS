<?php

namespace App\Generators\BackendUpdate;

use App\Generators\BaseGenerator;
use App\Models\Generator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;

Class  MigrationUpdateGenerator extends BaseGenerator
{
	/** @var $service */
	public $serviceGenerator;

	/** @var $service */
	public $serviceFile;

	/** @var string */
	public $path;

	public function __construct($generator, $model, $updateFields)
	{
		$this->serviceGenerator = new GeneratorService();
		$this->serviceFile = new FileService();
		$this->path = config('generator.path.laravel.migration');

        $this->generate($generator, $updateFields, $model);
		if(!empty($updateFields['changeFields'])) {
			sleep(1); // sleep 1 second to create migrate run after
			$this->generateChange($generator, $updateFields, $model);
		}
	}

	private function generate($generator, $updateFields, $model)
	{
		$now = Carbon::now();
		$pathTemplate = 'Databases/Migrations/';
		$templateData = $this->serviceGenerator->get_template("migrationUpdate", $pathTemplate);
		$templateData = str_replace('{{FIELDS_UP}}', $this->generateFieldsUp($updateFields), $templateData);
		$templateData = str_replace('{{FIELDS_DOWN}}', $this->generateFieldsDown($generator, $updateFields), $templateData);
		$templateData = str_replace('{{DATE_TIME}}', $now->toDateTimeString(), $templateData);

		$templateData = str_replace('{{TABLE_NAME_TITLE}}', $this->serviceGenerator->modelNamePlural($model['name']), $templateData);
		$templateData = str_replace('{{TABLE_NAME}}', $this->serviceGenerator->tableName($model['name']), $templateData);
		$fileName = date('Y_m_d_His').'_'.'update_'.$this->serviceGenerator->tableName($model['name']).'_table.php';

		$this->serviceFile->createFile($this->path, $fileName, $templateData);
	}

	private function generateChange($generator, $updateFields, $model)
    {
		$now = Carbon::now();
		$pathTemplate = 'Databases/Migrations/';
		$templateData = $this->serviceGenerator->get_template("migrationChange", $pathTemplate);
		$templateData = str_replace('{{FIELDS_UP}}', $this->generateFieldsChangeUp($generator, $updateFields), $templateData);
		$templateData = str_replace('{{FIELDS_DOWN}}', $this->generateFieldsChangeDown($generator, $updateFields), $templateData);
		$templateData = str_replace('{{DATE_TIME}}', $now->toDateTimeString(), $templateData);

		$templateData = str_replace('{{TABLE_NAME_TITLE}}', $this->serviceGenerator->modelNamePlural($model['name']), $templateData);
		$templateData = str_replace('{{TABLE_NAME}}', $this->serviceGenerator->tableName($model['name']), $templateData);
		$fileName = date('Y_m_d_His').'_'.'change_'.$this->serviceGenerator->tableName($model['name']).'_table.php';

		$this->serviceFile->createFile($this->path, $fileName, $templateData);
	}

	private function generateFieldsUp($updateFields)
	{
		$fieldsGenerate = [];

		$configDBType = config('generator.db_type');
		$configDefaultValue = config('generator.default_value');

		foreach($updateFields['updateFields'] as $index => $field) {
			$table = '';
			foreach ($configDBType as $typeLaravel => $typeDB) {
				if($field['db_type'] === $configDBType['enum']) {
					$enum = '';
					foreach($field['enum'] as $keyEnum => $value) {
						if($keyEnum === count($field['enum']) - 1) {
							$enum .= "'$value'";
						} else {
							$enum .= "'$value'" . ',';
						}
					}
					$table .= '$table->enum("'.$field['field_name'].'", ['.$enum.'])';
					break;
				}

				if($field['db_type'] === $configDBType['file']) {
					$table .= '$table->longText("'.$field['field_name'].'")';
					break;
				}

				if($field['db_type'] === $typeDB) {
					$table .= '$table->'.$typeLaravel.'("'.$field['field_name'].'")';
					break;
				}
			}

			if($field['default_value'] === $configDefaultValue['null']) {
				$table .= '->nullable()';
			} else if($field['default_value'] === $configDefaultValue['as_define']) {
				$table .= '->nullable()->default("'.$field['as_define'].'")';
			}
			if($table) {
				$table .= '; // Update';
				$fieldsGenerate[] = $table;
			}
		}

		foreach($updateFields['renameFields'] as $rename) {
			$tableRename = '$table->renameColumn("'.$rename['field_name_old']['field_name'].'", "'.$rename['field_name_new']['field_name'].'"); // Rename';
			$fieldsGenerate[] = $tableRename;
		}

		$dropFields = '';
		foreach ($updateFields['dropFields'] as $index => $drop) {
			$name = $drop['field_name'];
			if($index === count($updateFields['dropFields']) - 1) {
				$dropFields .= "'$name'";
			} else {
				$dropFields .= "'$name'" . ',';
			}
		}
		if($dropFields) {
			$tableDrop = '$table->dropColumn(['.$dropFields.']); // Drop';
			$fieldsGenerate[] = $tableDrop;
		}

		return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
	}
	private function generateFieldsDown($generator, $updateFields)
	{
		$fieldsGenerate = [];

		$configDBType = config('generator.db_type');
		$configDefaultValue = config('generator.default_value');
		foreach($updateFields['updateFields'] as $index => $field) {
			$fieldsGenerate[] = '$table->dropColumn("'.$field['field_name'].'");//Drop Update';
		}

		foreach($updateFields['renameFields'] as $rename) {
			$tableRename = '$table->renameColumn("'.$rename['field_name_new']['field_name'].'", "'.$rename['field_name_old']['field_name'].'"); // Reverse Rename';
			$fieldsGenerate[] = $tableRename;
		}

		$formFields = json_decode($generator->field, true);
		
		$arrayDrops = [];
		
		foreach($updateFields['dropFields'] as $drop) {
			$arrayDrops[] = $drop['field_name'];
		}

		foreach($formFields as $change) {
			if(in_array($change['field_name'], $arrayDrops)) {
				$tableDrop = '';
				foreach ($configDBType as $typeLaravel => $typeDB) {
					if($change['db_type'] === $configDBType['enum']) {
						$enum = '';
						foreach($change['enum'] as $keyEnum => $value) {
							if($keyEnum === count($change['enum']) - 1) {
								$enum .= "'$value'";
							} else {
								$enum .= "'$value'" . ',';
							}
						}
						$tableDrop .= '$table->enum("'.$change['field_name'].'", ['.$enum.'])';
						break;
					}

					if($change['db_type'] === $configDBType['file']) {
						$tableDrop .= '$table->longText("'.$change['field_name'].'")';
						break;
					}

					if($change['db_type'] === $typeDB) {
						$tableDrop .= '$table->'.$typeLaravel.'("'.$change['field_name'].'")';
						break;
					}
				}
				if($change['default_value'] === $configDefaultValue['null']) {
					$tableDrop .= '->nullable()';
				} else if($change['default_value'] === $configDefaultValue['as_define']) {
					$tableDrop .= '->nullable()->default("'.$change['as_define'].'")';
				}
				if($tableDrop) {
					$tableDrop .= '; // Add Drop Func Up';
					$fieldsGenerate[] = $tableDrop;
				}
			}
		}

		return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
	}
    private function generateFieldsChangeUp($generator, $updateFields)
    {
        $fieldsGenerate = [];
        $formFields = json_decode($generator->field, true);

        $configDBType = config('generator.db_type');
        $configDefaultValue = config('generator.default_value');

        foreach($updateFields['changeFields'] as $change) {
            foreach($formFields as $index => $field) {
                if($change['id'] === $field['id']) {
                    if($change['db_type'] !== $field['db_type']) {
                        $tableChange = '';
                        foreach ($configDBType as $typeLaravel => $typeDB) {
                            if($change['db_type'] === $configDBType['enum']) {
                                break;
                            }
                            if($change['db_type'] === $configDBType['file']) {
                                $tableChange .= '$table->longText("'.$change['field_name'].'")';
                                break;
                            }

                            if($change['db_type'] === $typeDB) {
                                $tableChange .= '$table->'.$typeLaravel.'("'.$change['field_name'].'")';
                                break;
                            }
                        }
                        if($change['default_value'] === $configDefaultValue['null']) {
                            $tableChange .= '->nullable()';
                        } else if($change['default_value'] === $configDefaultValue['as_define']) {
                            $tableChange .= '->nullable()->default("'.$change['as_define'].'")';
                        }
                        $tableChange .= '->change(); // Change';
                        $fieldsGenerate[] = $tableChange;
                    }
                }
            }
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
    }
	private function generateFieldsChangeDown($generator, $updateFields)
	{
		$fieldsGenerate = [];

		$configDBType = config('generator.db_type');
		$configDefaultValue = config('generator.default_value');

		$formFields = json_decode($generator->field, true);

        foreach($updateFields['changeFields'] as $changeNew) {
		    foreach($formFields as $change) {
				if($change['id'] === $changeNew['id']) {
                    if($change['db_type'] !== $changeNew['db_type']) {
                        $tableChange = '';
                        foreach ($configDBType as $typeLaravel => $typeDB) {
                            if($change['db_type'] === $configDBType['enum']) {
                                break;
                            }

                            if($change['db_type'] === $configDBType['file']) {
                                $tableChange .= '$table->longText("'.$change['field_name'].'")';
                                break;
                            }

                            if($change['db_type'] === $typeDB) {
                                $tableChange .= '$table->'.$typeLaravel.'("'.$change['field_name'].'")';
                                break;
                            }
                        }
                        if($change['default_value'] === $configDefaultValue['null']) {
                            $tableChange .= '->nullable()';
                        } else if($change['default_value'] === $configDefaultValue['as_define']) {
                            $tableChange .= '->nullable()->default("'.$change['as_define'].'")';
                        }
                        if($tableChange) {
                            $tableChange .= '->change(); // Reverse change';
                            $fieldsGenerate[] = $tableChange;
                        }
                    }
				}
			}
		}

		return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
	}
}