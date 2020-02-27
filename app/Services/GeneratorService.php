<?php

namespace App\Service;

class GeneratorService extends BaseService
{
    /**
     * Generates tab with spaces.
     *
     * @param int $spaces
     *
     * @return string
     */
    public function infy_tab($spaces = 4)
    {
        return str_repeat(' ', $spaces);
    }

    /**
     * Generates tab with spaces.
     *
     * @param int $tabs
     * @param int $spaces
     *
     * @return string
     */
    public function infy_tabs($tabs, $spaces = 4)
    {
        return str_repeat($this->infy_tab($spaces), $tabs);
    }

    /**
     * Generates new line char.
     *
     * @param int $count
     *
     * @return string
     */
    public function infy_nl($count = 1)
    {
        return str_repeat(PHP_EOL, $count);
    }

    /**
     * Generates new line char.
     *
     * @param int $count
     * @param int $nls
     *
     * @return string
     */
    public function infy_nls($count, $nls = 1)
    {
        return str_repeat($this->infy_nl($nls), $count);
    }

    /**
     * Generates new line char.
     *
     * @param int $lns
     * @param int $tabs
     * @param int $spaces
     *
     * @return string
     */
    public function infy_nl_tab($lns = 1, $tabs = 1, $spaces = 4)
    {
        return $this->infy_nls($lns) . $this->infy_tabs($tabs, $spaces);
    }

    /**
     * get path for template file.
     *
     * @param string $templateName
     * @param string $templatePath
     * @param string $typeTemplate
     *
     * @return string
     */
    public function get_template_file_path($templateName, $templatePath, $typeTemplate = 'laravel')
    {
        if ($typeTemplate === 'laravel') {
            $templatesPath = config('generator.template.laravel');
        } else {
            $templatesPath = config('generator.template.vuejs');
        }
        $path = $templatesPath . $templatePath . $templateName . '.stub';
        if (file_exists($path)) {
            return $path;
        }
        return false;
    }

    /**
     * get path for file.
     *
     * @param string $nameConfig
     * @param string $type
     * @param string $fileName
     *
     * @return string
     */
    public function getFilePath($nameConfig, $type = 'laravel', $fileName = '')
    {
        if ($type === 'laravel') {
            $path = config('generator.path.laravel.' . $nameConfig);
        } else {
            $path = config('generator.path.vuejs.' . $nameConfig);
        }
        if ($fileName) {
            $path = $path . $fileName;
        }
        if (file_exists($path)) {
            return $path;
        }
        return false;
    }

    /**
     * get file.
     *
     * @param string $nameConfig
     * @param string $type
     * @param string $fileName
     *
     * @return string
     */
    public function getFile($nameConfig, $type = 'laravel', $fileName = '')
    {
        $path = $this->getFilePath($nameConfig, $type, $fileName);
        return file_get_contents($path);
    }

    /**
     * get path for file.
     *
     * @param string $nameConfig
     * @param string $type
     * @param string $fileName
     *
     * @return string
     */
    public function getFilePathReal($fileName = '', $type = 'laravel')
    {
        if ($type === 'laravel') {
            $path = config('generator.path.laravel');
        } else {
            $path = config('generator.path.vuejs.resource_js');
        }
        if ($fileName) {
            $path = $path . $fileName;
        }
        if (file_exists($path)) {
            return $path;
        }
        return false;
    }

    /**
     * get file.
     *
     * @param string $nameConfig
     * @param string $type
     * @param string $fileName
     *
     * @return string
     */
    public function getFileReal($fileName = '', $type = 'laravel')
    {
        $path = $this->getFilePathReal($fileName, $type);
        return file_get_contents($path);
    }

    /**
     * get template contents.
     *
     * @param string $templateName
     * @param string $templatePath
     * @param string $typeTemplate
     *
     * @return string
     */
    public function get_template($templateName, $templatePath, $typeTemplate = 'laravel')
    {
        $path = $this->get_template_file_path($templateName, $templatePath, $typeTemplate);
        return file_get_contents($path);
    }

    /**
     * get template contents.
     *
     * @param string $templateName
     * @param string $templatePath
     * @param string $typeTemplate
     *
     * @return string
     */
    public function getFileExist($templateName, $templatePath, $typeTemplate = 'laravel')
    {
        $path = $this->get_template_file_path($templateName, $templatePath, $typeTemplate);
        return file_get_contents($path);
    }

    /**
     * fill template with variable values.
     *
     * @param array $variables
     * @param string $template
     *
     * @return string
     */

    public function fill_template($variables, $template)
    {
        foreach ($variables as $variable => $value) {
            $template = str_replace($variable, $value, $template);
        }
        return $template;
    }

    /**
     * fill field template with variable values.
     *
     * @param array $variables
     * @param string $template
     * @param \InfyOm\Generator\Common\GeneratorField $field
     *
     * @return string
     */
    public function fill_field_template($variables, $template, $field)
    {
        foreach ($variables as $variable => $key) {
            $template = str_replace($variable, $field->$key, $template);
        }
        return $template;
    }

    /**
     * fill template with field data.
     *
     * @param array $variables
     * @param array $fieldVariables
     * @param string $template
     * @param \InfyOm\Generator\Common\GeneratorField $field
     *
     * @return string
     */
    public function fill_template_with_field_data($variables, $fieldVariables, $template, $field)
    {
        $template = $this->fill_template($variables, $template);
        return $this->fill_field_template($fieldVariables, $template, $field);
    }

    /**
     * generates model name from table name.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function modelNamePlural($tableName)
    {
        return ucfirst(\Str::camel(\Str::plural($tableName)));
    }

    /**
     * generates model name from table name.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function modelNameNotPlural($tableName)
    {
        return ucfirst(\Str::camel($tableName));
    }

    /**
     * generates model name from table name frontend.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function modelNameNotPluralFe($tableName)
    {
        return \Str::camel($tableName);
    }

    /**
     * generates model name from table name frontend.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function modelNamePluralFe($tableName)
    {
        return \Str::camel(\Str::plural($tableName));
    }

    /**
     * generates model name from table name.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function urlResource($tableName)
    {
        return lcfirst(\Str::kebab(\Str::plural($tableName)));
    }

    /**
     * generates folder name from model name.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function folderPages($tableName)
    {
        return lcfirst(\Str::kebab($tableName));
    }

    /**
     * generates folder name from model name.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function nameAttribute($tableName)
    {
        return lcfirst(\Str::kebab($tableName));
    }

    /**
     * generates model name from table name.
     *
     * @param string $name
     *
     * @return string
     */
    public function tableName($name)
    {
        return \Str::snake(\Str::plural($name));
    }

    /**
     * generates model name from table name.
     *
     * @param string $name
     *
     * @return string
     */
    public function tableNameNotPlural($name)
    {
        return \Str::snake($name);
    }

    /**
     * generates model name from table name.
     *
     * @param string $tableName
     *
     * @return string
     */
    public function urlFilterColumn($key, $type, $value = '', $singleSorting = true)
    {
        $params = \Request::all();
        if (isset($params['filter_column']) && $singleSorting) {
            foreach ($params['filter_column'] as $k => $filter) {
                foreach ($filter as $t => $val) {
                    if ($t == 'sorting') {
                        unset($params['filter_column'][$k]['sorting']);
                    }
                }
            }
        }
        $params['filter_column'][$key][$type] = $value;
        return \Request::url() . '?' . http_build_query($params);
    }

    /**
     * check options.
     *
     * @param array $options
     * @param string $name
     *
     * @return string
     */
    public function getOptions(string $name, array $options)
    {
        return in_array($name, $options);
    }

    /**
     * Replace comment not delete.
     *
     * @param string $noteDelete
     * @param string $replace
     * @param number $tab
     * @param string $templateDataReal
     * @param integer $spaces
     *
     * @return string
     */
    public function replaceNotDelete($noteDelete, $replace, $tab, $templateDataReal, $spaces = 4)
    {
        return str_replace(
            $noteDelete,
            $replace . $this->infy_nl_tab(1, $tab, $spaces) . $noteDelete,
            $templateDataReal,
        );
    }

    /**
     * search string template.
     *
     * @param string $search
     * @param string $char
     * @param number $plusStart
     * @param number $plusEnd
     * @param string $templateDataReal
     *
     * @param string $searchOther
     * @return string
     */
    public function searchTemplate($search, $char, $plusStart, $plusEnd, $templateDataReal, $searchOther = '')
    {
        if (!$searchOther) {
            $searchOther = $search;
        }
        if (strpos($templateDataReal, $searchOther)) {
            $template = substr($templateDataReal, stripos($templateDataReal, $search));
            $length = stripos($template, $char);
            return substr($templateDataReal, stripos($templateDataReal, $search) + $plusStart, $length + $plusEnd);
        }
        return false;
    }

    /**
     * search string with position X template.
     *
     * @param string $search
     * @param number $number
     * @param string $char
     * @param number $plusStart
     * @param number $plusEnd
     * @param string $templateDataReal
     *
     * @return string
     */
    public function searchTemplateX($search, $number, $char, $plusStart, $plusEnd, $templateDataReal)
    {
        $position = strpos_x($templateDataReal, $search, $number);
        if ($position) {
            $template = substr($templateDataReal, $position);
            $length = stripos($template, $char);
            return substr(
                $templateDataReal,
                $position + strlen($search) + $plusStart,
                $length + $plusEnd - strlen($search),
            );
        } else {
            return false;
        }
    }

    /**
     * get relationship on model
     *
     * @param string $model
     * @return array
     */
    public function getRelations($model)
    {
        $fileData = file(config('generator.path.laravel.model') . $model . '.php');
        $modelData = [];
        if (!in_array($model, config('generator.relationship.ignore_model'))) {
            $modelData[] = [
                'model' => $model,
                'data' => $this->extractRelations($fileData),
            ];
        }
        return $modelData;
    }

    /**
     * search relationship
     *
     * @param string $data
     * @return array
     */
    public function extractRelations($data)
    {
        $relationshipIdentifiers = config('generator.relationship.relationship');
        $relationshipData = [];
        //        $matchPattern = '#\((.*?)\)#';
        $matchPattern = '#(hasOne|belongsTo|hasMany|belongsToMany)\((.*?)\)#';
        foreach ($data as $line) {
            foreach ($relationshipIdentifiers as $relationship) {
                $nameRelationship = $relationship . '(';
                $searchRelationship = $this->searchTemplateX(
                    $nameRelationship,
                    1,
                    ')',
                    -strlen($nameRelationship),
                    strlen($nameRelationship),
                    $line,
                );
                if ($searchRelationship) {
                    $modelData = explode(',', $searchRelationship);
                    $modelName = $this->stripString($modelData[0], $relationship);
                    if ($relationship === 'belongsToMany') {
                        $tableName = $this->modelNameNotPlural($this->stripString($modelData[1], $relationship));
                        $subModel = substr($tableName, strlen($modelName));
                        $relationshipData[] = [
                            'type' => $relationship,
                            'model' => $modelName,
                            'table' => $tableName,
                            'foreign_key' => $this->stripString(
                                isset($modelData[2]) ? $modelData[2] : \Str::snake($subModel) . '_id',
                            ),
                            'local_key' => $this->stripString(
                                isset($modelData[3]) ? $modelData[3] : \Str::snake($modelName) . '_id',
                            ),
                        ];
                    } else {
                        $relationshipData[] = [
                            'type' => $relationship,
                            'model' => $modelName,
                            'foreign_key' => $this->stripString(
                                isset($modelData[1]) ? $modelData[1] : \Str::snake($modelName) . '_id',
                            ),
                            'local_key' => $this->stripString(isset($modelData[2]) ? $modelData[2] : 'id'),
                        ];
                    }
                }
            }
        }
        return collect($relationshipData);
    }

    /**
     * trip strings from slashes, App, class and ::
     *
     * @param string $string
     * @param string $relationship
     * @return string
     */
    public function stripString($string, $relationship = '')
    {
        $string = str_replace('App', '', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace('\\', '', $string);
        $string = str_replace('Models', '', $string);
        $string = str_replace('::', '', $string);
        $string = str_replace('class', '', $string);
        $string = str_replace($relationship, '', $string);
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);
        $string = str_replace(' ', '', $string);
        return $string;
    }

    /**
     * Trim quotes
     * @param $string
     * @return string
     */
    public function trimQuotes($string)
    {
        $string = trim($string, "'");
        $string = trim($string, '"');
        return $string;
    }
}
