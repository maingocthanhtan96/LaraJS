<?php

namespace App\Generators\FrontendUpdate;

use App\Generators\BaseGenerator;
use App\Service\FileService;
use App\Service\GeneratorService;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;


Class ViewUpdateGenerator extends BaseGenerator
{
    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $path;

    /** @var string */
    public $notDelete;

    /** @var string */
    public $dbType;

    const COLUMNS = 'columns';
    const HEADINGS = 'headings';
    const COLUMNS_CLASSES = 'columnsClasses';
    const SORTABLE = 'sortable';
    const CREATE_AT = 'created_at';
    const ACTIONS = 'actions';
    const TEMPLATE_START = '<template data-generator=';
    const TEMPLATE_END = '</template>';

    public function __construct($model, $updateFields)
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->path = config('generator.path.vuejs.views');
        $this->notDelete = config('generator.not_delete.vuejs.views');
        $this->dbType = config('generator.db_type');

        $this->generate($model, $updateFields);
    }

    private function generate($model, $updateFields)
    {
        $fileName = $this->serviceGenerator->modelNameNotPluralFe($model['name']) . '/index.vue';
        $templateDataReal = $this->serviceGenerator->getFile('views', 'vuejs', $fileName);
        $templateDataReal = $this->generateFieldsRename($updateFields['renameFields'], $model, $templateDataReal);
        $templateDataReal = $this->generateFieldsChange($updateFields['changeFields'], $model, $templateDataReal);
        $templateDataReal = $this->generateFieldsDrop($updateFields['dropFields'], $templateDataReal);
        $templateDataReal = $this->generateFieldsUpdate($updateFields['updateFields'], $model, $templateDataReal);
        $fileName = $this->path . $fileName;
        $this->serviceFile->createFileReal($fileName, $templateDataReal);
    }

    private function generateFieldsRename($renameFields, $model, $templateDataReal)
    {
        if (empty($renameFields)) {
            return $templateDataReal;
        }

        $selfColumns = self::COLUMNS;
        $selfHeadings = self::HEADINGS;
        $selfClasses = self::COLUMNS_CLASSES;
        $selfSortable = self::SORTABLE;
        $selfTemplateEnd = self::TEMPLATE_END;
        $fieldsGenerateColumns = [];
        $fieldsGenerateHeadings = [];
        $fieldsGenerateClasses = [];
        $fieldsGenerateSortable = [];
        $templateColumns = $this->serviceGenerator->searchTemplateX($selfColumns, 3, '],', 3, -3, $templateDataReal);
        $templateHeadings = $this->serviceGenerator->searchTemplateX($selfHeadings, 3, '},', 3, -3, $templateDataReal);
        $templateClasses = $this->serviceGenerator->searchTemplateX($selfClasses, 3, '},', 3, -3, $templateDataReal);
        $templateSortable = $this->serviceGenerator->searchTemplateX($selfSortable, 3, '],', 3, -3, $templateDataReal);
        $columns = explode(',', trim($templateColumns));
        $headings = explode(',', trim($templateHeadings));
        $classes = explode(',', trim($templateClasses));
        $sortable = explode(',', trim($templateSortable));
        $arrayChange = \Arr::pluck($renameFields, 'field_name_old.field_name');
        foreach ($renameFields as $index => $rename) {
            foreach ($columns as $col) {
                if (strlen($col) > 0) {
                    $col = trim($col);
                    $col = trim($col, "''");
                    if ($rename['field_name_old']['field_name'] === $col) {
                        $fieldsGenerateColumns[] = "'" . $rename['field_name_new']['field_name'] . "'";
                    } else {
                        $name = "'" . $col . "'";
                        if (!in_array($name, $fieldsGenerateColumns) && !in_array($col, $arrayChange)) {
                            if ($col === self::CREATE_AT || $col === self::ACTIONS) {
                                if ($index === count($renameFields) - 1) {
                                    if ($col === self::CREATE_AT) {
                                        $fieldsGenerateColumns[] = "'" . self::CREATE_AT . "'";
                                    }
                                    if ($col === self::ACTIONS) {
                                        $fieldsGenerateColumns[] = "'" . self::ACTIONS . "'";
                                    }
                                }
                            } else {
                                $fieldsGenerateColumns[] = $name;
                            }
                        }
                    }
                }
            }
            foreach ($headings as $heading) {
                if (strlen($heading) > 0) {
                    $heading = trim($heading);
                    list($keyHeading, $valHeading) = explode('() =>', $heading);
                    $keyHeading = trim($keyHeading);
                    $valHeading = trim($valHeading);
                    $keyHeading = trim($keyHeading, "'':");
                    if ($rename['field_name_old']['field_name'] === $keyHeading) {
                        $fieldsGenerateHeadings[] = "'" . $rename['field_name_new']['field_name'] . "'" . ': () => this.$t("table.' . $this->serviceGenerator->tableNameNotPlural($model['name']) . '.' . $rename['field_name_new']['field_name'] . '")' . ',';
                    } else {
                        $name = "'" . $keyHeading . "'" . ': () => ' . $valHeading . ',';
                        if (!in_array($name, $fieldsGenerateHeadings) && !in_array($keyHeading, $arrayChange)) {
                            if ($keyHeading === self::CREATE_AT) {
                                if ($index === count($renameFields) - 1) {
                                    $fieldsGenerateHeadings[] = $name;
                                }
                            } else {
                                $fieldsGenerateHeadings[] = $name;
                            }
                        }
                    }
                }
            }
            foreach ($classes as $class) {
                if (strlen($class) > 0) {
                    $class = trim($class);
                    list($keyClass, $valClass) = explode(': ', $class);
                    $keyClass = trim($keyClass);
                    $valClass = trim($valClass);
                    $keyClass = trim($keyClass, "'':");
                    if ($rename['field_name_old']['field_name'] === $keyClass) {
                        $fieldsGenerateClasses[] = "'" . $rename['field_name_new']['field_name'] . "': $valClass,";
                    } else {
                        $name = "'" . $keyClass . "': $valClass,";
                        if (!in_array($name, $fieldsGenerateClasses) && !in_array($keyClass, $arrayChange)) {
                            if ($keyClass === self::CREATE_AT) {
                                if ($index === count($renameFields) - 1) {
                                    $fieldsGenerateClasses[] = $name;
                                }
                            } else {
                                $fieldsGenerateClasses[] = $name;
                            }
                        }
                    }
                }
            }
            foreach ($sortable as $sort) {
                if (strlen($sort) > 0) {
                    $sort = trim($sort);
                    $sort = trim($sort, "''");
                    if ($rename['field_name_old']['field_name'] === $sort) {
                        $fieldsGenerateSortable[] = "'" . $rename['field_name_new']['field_name'] . "'";
                    } else {
                        $name = "'" . $sort . "'";
                        if (!in_array($name, $fieldsGenerateSortable) && !in_array($sort, $arrayChange)) {
                            if ($sort === self::CREATE_AT) {
                                if ($index === count($renameFields) - 1) {
                                    $fieldsGenerateSortable[] = $name;
                                }
                            } else {
                                $fieldsGenerateSortable[] = $name;
                            }
                        }
                    }
                }
            }

            //replace template index.view
            $selfTemplateStart = self::TEMPLATE_START;
            $selfTemplateStart .= '"' . $rename['field_name_old']['field_name'] . '"';
            if ($rename['field_name_old']['db_type'] === $this->dbType['longtext']) {
                $templateLongText = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
                $longTexts = explode(" ", $templateLongText);
                $fieldsGenerateLongText = $this->templateArray($longTexts, $rename);
                $templateDataReal = str_replace($templateLongText, implode(' ', $fieldsGenerateLongText), $templateDataReal);
            }
            if ($rename['field_name_old']['db_type'] === $this->dbType['file']) {
                $templateJson = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
                $files = explode(" ", $templateJson);
                $fieldsGenerateJson = $this->templateArray($files, $rename);
                $templateDataReal = str_replace($templateJson, implode(' ', $fieldsGenerateJson), $templateDataReal);
            }
        }
        $templateDataReal = str_replace("$selfColumns: [" . $templateColumns . "]", "$selfColumns: [" . implode(', ', $fieldsGenerateColumns) . "]", $templateDataReal);
        $templateDataReal = str_replace("$selfHeadings: {" . $templateHeadings
            . "},", "$selfHeadings: {" . $this->replaceTemplate($fieldsGenerateHeadings, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfClasses: {" . $templateClasses
            . "},", "$selfClasses: {" . $this->replaceTemplate($fieldsGenerateClasses, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfSortable: [" . $templateColumns . "]", "$selfSortable: [" . implode(', ', $fieldsGenerateSortable) . "]", $templateDataReal);

        return $templateDataReal;
    }

    private function generateFieldsChange($changeFields, $model, $templateDataReal)
    {
        if (empty($changeFields)) {
            return $templateDataReal;
        }

        $selfColumns = self::COLUMNS;
        $selfHeadings = self::HEADINGS;
        $selfClasses = self::COLUMNS_CLASSES;
        $selfSortable = self::SORTABLE;
        $selfTemplateEnd = self::TEMPLATE_END;
        $fieldsGenerateColumns = [];
        $fieldsGenerateHeadings = [];
        $fieldsGenerateClasses = [];
        $fieldsGenerateSortable = [];
        $templateColumns = $this->serviceGenerator->searchTemplateX($selfColumns, 3, '],', 3, -3, $templateDataReal);
        $templateHeadings = $this->serviceGenerator->searchTemplateX($selfHeadings, 3, '},', 3, -3, $templateDataReal);
        $templateClasses = $this->serviceGenerator->searchTemplateX($selfClasses, 3, '},', 3, -3, $templateDataReal);
        $templateSortable = $this->serviceGenerator->searchTemplateX($selfSortable, 3, '],', 3, -3, $templateDataReal);
        $columns = explode(',', trim($templateColumns));
        $headings = explode(',', trim($templateHeadings));
        $classes = explode(',', trim($templateClasses));
        $sortable = explode(',', trim($templateSortable));
        $arrayChange = \Arr::pluck($changeFields, 'field_name');
        foreach ($changeFields as $index => $update) {
            foreach ($columns as $col) {
                if (strlen($col) > 0) {
                    $col = trim($col);
                    $col = trim($col, "''");
                    if ($update['field_name'] === $col) {
                        if ($update['show']) {
                            $fieldsGenerateColumns[] = "'" . $update['field_name'] . "'";
                        }
                    } else {
                        $name = "'" . $col . "'";
                        if (!in_array($name, $fieldsGenerateColumns) && !in_array($col, $arrayChange)) {
                            if ($col === self::CREATE_AT || $col === self::ACTIONS) {
                                if ($index === count($changeFields) - 1) {
                                    if ($col === self::CREATE_AT) {
                                        $fieldsGenerateColumns[] = "'" . self::CREATE_AT . "'";
                                    }
                                    if ($col === self::ACTIONS) {
                                        $fieldsGenerateColumns[] = "'" . self::ACTIONS . "'";
                                    }
                                }
                            } else {
                                $fieldsGenerateColumns[] = $name;
                            }
                        }
                    }
                }
            }
            foreach ($headings as $heading) {
                if (strlen($heading) > 0) {
                    $heading = trim($heading);
                    list($keyHeading, $valHeading) = explode('() =>', $heading);
                    $keyHeading = trim($keyHeading);
                    $valHeading = trim($valHeading);
                    $keyHeading = trim($keyHeading, "'':");
                    if ($update['field_name'] === $keyHeading) {
                        if ($update['show']) {
                            $fieldsGenerateHeadings[] = "'" . $update['field_name'] . "'" . ': () => this.$t("table.' . $this->serviceGenerator->tableNameNotPlural($model['name']) . '.' . $update['field_name'] . '")' . ',';
                        }
                    } else {
                        $name = "'" . $keyHeading . "'" . ': () => ' . $valHeading . ',';
                        if (!in_array($name, $fieldsGenerateHeadings) && !in_array($keyHeading, $arrayChange)) {
                            if ($keyHeading === self::CREATE_AT) {
                                if ($index === count($changeFields) - 1) {
                                    $fieldsGenerateHeadings[] = $name;
                                }
                            } else {
                                $fieldsGenerateHeadings[] = $name;
                            }
                        }
                    }
                }
            }
            foreach ($classes as $class) {
                if (strlen($class) > 0) {
                    $class = trim($class);
                    list($keyClass, $valClass) = explode(': ', $class);
                    $keyClass = trim($keyClass);
                    $valClass = trim($valClass);
                    $keyClass = trim($keyClass, "'':");
                    if ($update['field_name'] === $keyClass) {
                        if ($update['show']) {
                            switch ($update['db_type']) {
                                case 'Increments':
                                case $this->dbType['integer']:
                                case $this->dbType['bigInteger']:
                                case $this->dbType['float']:
                                case $this->dbType['double']:
                                case $this->dbType['boolean']:
                                case $this->dbType['date']:
                                case $this->dbType['dateTime']:
                                case $this->dbType['time']:
                                case $this->dbType['year']:
                                case $this->dbType['enum']:
                                case $this->dbType['file']:
                                    $fieldsGenerateClasses[] = "'" . $update['field_name'] . "': 'text-center',";
                                    break;
                            }
                        }
                    } else {
                        $name = "'" . $keyClass . "': $valClass,";
                        if (!in_array($name, $fieldsGenerateClasses) && !in_array($keyClass, $arrayChange)) {
                            if ($keyClass === self::CREATE_AT) {
                                if ($index === count($changeFields) - 1) {
                                    $fieldsGenerateClasses[] = $name;
                                }
                            } else {
                                $fieldsGenerateClasses[] = $name;
                            }
                        }
                        $name = "'" . $update['field_name'] . "': 'text-center',";
                        if (!in_array($name, $fieldsGenerateClasses)) {
                            if ($update['show']) {
                                switch ($update['db_type']) {
                                    case 'Increments':
                                    case $this->dbType['integer']:
                                    case $this->dbType['bigInteger']:
                                    case $this->dbType['float']:
                                    case $this->dbType['double']:
                                    case $this->dbType['boolean']:
                                    case $this->dbType['date']:
                                    case $this->dbType['dateTime']:
                                    case $this->dbType['time']:
                                    case $this->dbType['year']:
                                    case $this->dbType['enum']:
                                    case $this->dbType['file']:
                                        $fieldsGenerateClasses[] = $name;
                                        break;
                                }
                            }
                        }
                    }
                }
            }
            foreach ($sortable as $sort) {
                if (strlen($sort) > 0) {
                    $sort = trim($sort);
                    $sort = trim($sort, "''");
                    if ($update['field_name'] === $sort) {
                        if ($update['sort']) {
                            $fieldsGenerateSortable[] = "'" . $update['field_name'] . "'";
                        }
                    } else {
                        $name = "'" . $sort . "'";
                        if (!in_array($name, $fieldsGenerateSortable) && !in_array($sort, $arrayChange)) {
                            if ($sort === self::CREATE_AT) {
                                if ($index === count($changeFields) - 1) {
                                    $fieldsGenerateSortable[] = $name;
                                }
                            } else {
                                $fieldsGenerateSortable[] = $name;
                            }
                        }
                    }
                }
            }

            //replace template index.view
            $selfTemplateStart = self::TEMPLATE_START;
            $selfTemplateStart .= '"' . $update['field_name'] . '"';
            if ($update['db_type'] === $this->dbType['longtext']) {
                $templateLongText = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
                if ($templateLongText) {
                    if (!$update['show']) {
                        $templateDataReal = str_replace($templateLongText, '', $templateDataReal);
                    }
                } else {
                    if ($update['show']) {
                        $templateDataReal = $this->generateHandler($update);
                    }
                }
            }
            if ($update['db_type'] === $this->dbType['file']) {
                $templateJson = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
                if ($templateJson) {
                    if (!$update['show']) {
                        $templateDataReal = str_replace($templateJson, '', $templateDataReal);
                    }
                } else {
                    if ($update['show']) {
                        $templateDataReal = $this->generateHandler($update);
                    }
                }
            }
        }
        $templateDataReal = str_replace("$selfColumns: [" . $templateColumns . "]", "$selfColumns: [" . implode(', ', $fieldsGenerateColumns) . "]", $templateDataReal);
        $templateDataReal = str_replace("$selfHeadings: {" . $templateHeadings
            . "},", "$selfHeadings: {" . $this->replaceTemplate($fieldsGenerateHeadings, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfClasses: {" . $templateClasses
            . "},", "$selfClasses: {" . $this->replaceTemplate($fieldsGenerateClasses, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfSortable: [" . $templateColumns . "]", "$selfSortable: [" . implode(', ', $fieldsGenerateSortable) . "]", $templateDataReal);
        return $templateDataReal;
    }

    private function generateFieldsDrop($dropFields, $templateDataReal)
    {
        if (empty($dropFields)) {
            return $templateDataReal;
        }

        $selfColumns = self::COLUMNS;
        $selfHeadings = self::HEADINGS;
        $selfClasses = self::COLUMNS_CLASSES;
        $selfSortable = self::SORTABLE;
        $selfTemplateEnd = self::TEMPLATE_END;
        $fieldsGenerateColumns = [];
        $fieldsGenerateHeadings = [];
        $fieldsGenerateClasses = [];
        $fieldsGenerateSortable = [];
        $templateColumns = $this->serviceGenerator->searchTemplateX($selfColumns, 3, '],', 3, -3, $templateDataReal);
        $templateHeadings = $this->serviceGenerator->searchTemplateX($selfHeadings, 3, '},', 3, -3, $templateDataReal);
        $templateClasses = $this->serviceGenerator->searchTemplateX($selfClasses, 3, '},', 3, -3, $templateDataReal);
        $templateSortable = $this->serviceGenerator->searchTemplateX($selfSortable, 3, '],', 3, -3, $templateDataReal);
        $columns = explode(',', trim($templateColumns));
        $headings = explode(',', trim($templateHeadings));
        $classes = explode(',', trim($templateClasses));
        $sortable = explode(',', trim($templateSortable));
        foreach ($dropFields as $index => $drop) {
            foreach ($columns as $col) {
                if (strlen($col) > 0) {
                    $col = trim($col);
                    $col = trim($col, "''");
                    $name = "'" . $col . "'";
                    if ($drop['field_name'] !== $col && !in_array($name, $fieldsGenerateColumns)) {
                        if ($col === self::CREATE_AT || $col === self::ACTIONS) {
                            if ($index === count($dropFields) - 1) {
                                if ($col === self::CREATE_AT) {
                                    $fieldsGenerateColumns[] = "'" . self::CREATE_AT . "'";
                                }
                                if ($col === self::ACTIONS) {
                                    $fieldsGenerateColumns[] = "'" . self::ACTIONS . "'";
                                }
                            }
                        } else {
                            $fieldsGenerateColumns[] = $name;
                        }
                    }
                }
            }
            foreach ($headings as $heading) {
                if (strlen($heading) > 0) {
                    $heading = trim($heading);
                    list($keyHeading, $valHeading) = explode('() =>', $heading);
                    $keyHeading = trim($keyHeading);
                    $valHeading = trim($valHeading);
                    $keyHeading = trim($keyHeading, "'':");
                    $name = "'" . $keyHeading . "'" . ': () => ' . $valHeading . ',';
                    if ($drop['field_name'] !== $keyHeading && !in_array($name, $fieldsGenerateHeadings)) {
                        if ($keyHeading === self::CREATE_AT) {
                            if ($index === count($dropFields) - 1) {
                                $fieldsGenerateHeadings[] = $name;
                            }
                        } else {
                            $fieldsGenerateHeadings[] = $name;
                        }
                    }
                }
            }
            foreach ($classes as $class) {
                if (strlen($class) > 0) {
                    $class = trim($class);
                    list($keyClass, $valClass) = explode(': ', $class);
                    $keyClass = trim($keyClass);
                    $valClass = trim($valClass);
                    $keyClass = trim($keyClass, "'':");
                    $name = "'" . $keyClass . "': $valClass,";
                    if ($drop['field_name'] !== $keyClass && !in_array($name, $fieldsGenerateClasses)) {
                        if ($keyClass === self::CREATE_AT) {
                            if ($index === count($dropFields) - 1) {
                                $fieldsGenerateClasses[] = $name;
                            }
                        } else {
                            $fieldsGenerateClasses[] = $name;
                        }
                    }
                }
            }
            foreach ($sortable as $sort) {
                if (strlen($sort) > 0) {
                    $sort = trim($sort);
                    $sort = trim($sort, "''");
                    $name = "'" . $sort . "'";
                    if ($drop['field_name'] !== $sort && !in_array($name, $fieldsGenerateSortable)) {
                        if ($sort === self::CREATE_AT) {
                            if ($index === count($dropFields) - 1) {
                                $fieldsGenerateSortable[] = $name;
                            }
                        } else {
                            $fieldsGenerateSortable[] = $name;
                        }
                    }
                }
            }

            //replace template index.view
            $selfTemplateStart = self::TEMPLATE_START;
            $selfTemplateStart .= '"' . $drop['field_name'] . '"';
            if ($drop['db_type'] === $this->dbType['longtext']) {
                $templateLongText = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
                if ($templateLongText) {
                    $templateDataReal = str_replace($templateLongText, '', $templateDataReal);
                }
            }
            if ($drop['db_type'] === $this->dbType['file']) {
                $templateJson = $this->serviceGenerator->searchTemplateX($selfTemplateStart, 1, $selfTemplateEnd, -strlen($selfTemplateStart), strlen($selfTemplateStart) + strlen($selfTemplateEnd), $templateDataReal);
                if ($templateJson) {
                    $templateDataReal = str_replace($templateJson, '', $templateDataReal);
                }
            }
        }
        $templateDataReal = str_replace("$selfColumns: [" . $templateColumns . "]", "$selfColumns: [" . implode(', ', $fieldsGenerateColumns) . "]", $templateDataReal);
        $templateDataReal = str_replace("$selfHeadings: {" . $templateHeadings
            . "},", "$selfHeadings: {" . $this->replaceTemplate($fieldsGenerateHeadings, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfClasses: {" . $templateClasses
            . "},", "$selfClasses: {" . $this->replaceTemplate($fieldsGenerateClasses, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfSortable: [" . $templateColumns . "]", "$selfSortable: [" . implode(', ', $fieldsGenerateSortable) . "]", $templateDataReal);
        return $templateDataReal;
    }

    private function generateFieldsUpdate($updateFields, $model, $templateDataReal)
    {
        if (empty($updateFields)) {
            return $templateDataReal;
        }

        $selfColumns = self::COLUMNS;
        $selfHeadings = self::HEADINGS;
        $selfClasses = self::COLUMNS_CLASSES;
        $selfSortable = self::SORTABLE;
        $fieldsGenerateColumns = [];
        $fieldsGenerateHeadings = [];
        $fieldsGenerateClasses = [];
        $fieldsGenerateSortable = [];
        $templateColumns = $this->serviceGenerator->searchTemplateX($selfColumns, 3, '],', 3, -3, $templateDataReal);
        $templateHeadings = $this->serviceGenerator->searchTemplateX($selfHeadings, 3, '},', 3, -3, $templateDataReal);
        $templateClasses = $this->serviceGenerator->searchTemplateX($selfClasses, 3, '},', 3, -3, $templateDataReal);
        $templateSortable = $this->serviceGenerator->searchTemplateX($selfSortable, 3, '],', 3, -3, $templateDataReal);
        $columns = explode(',', trim($templateColumns));
        $headings = explode(',', trim($templateHeadings));
        $classes = explode(',', trim($templateClasses));
        $sortable = explode(',', trim($templateSortable));

        foreach ($columns as $col) {
            if (strlen($col) > 0) {
                $col = trim($col);
                $col = trim($col, "''");
                $name = "'" . $col . "'";
                if ($name !== "'" . self::CREATE_AT . "'" && $name !== "'" . self::ACTIONS . "'") {
                    $fieldsGenerateColumns[] = $name;
                }
            }
        }
        $createHeading = "'" . self::CREATE_AT . "': () => " . 'this.$t(\'date.created_at\'),';
        foreach ($headings as $heading) {
            if (strlen($heading) > 0) {
                $heading = trim($heading);
                list($keyHeading, $valHeading) = explode('() =>', $heading);
                $keyHeading = trim($keyHeading);
                $valHeading = trim($valHeading);
                $keyHeading = trim($keyHeading, "'':");
                $name = "'" . $keyHeading . "'" . ': () => ' . $valHeading . ',';
                if ($name !== $createHeading) {
                    $fieldsGenerateHeadings[] = $name;
                }
            }
        }
        $createClasses = "'" . self::CREATE_AT . "': 'text-center',";
        foreach ($classes as $class) {
            if (strlen($class) > 0) {
                $class = trim($class);
                list($keyClass, $valClass) = explode(': ', $class);
                $keyClass = trim($keyClass);
                $valClass = trim($valClass);
                $keyClass = trim($keyClass, "'':");
                $name = "'" . $keyClass . "': $valClass,";
                if ($name !== $createClasses) {
                    $fieldsGenerateClasses[] = $name;
                }
            }
        }
        $createSort = "'" . self::CREATE_AT . "'";
        foreach ($sortable as $sort) {
            if (strlen($sort) > 0) {
                $sort = trim($sort);
                $sort = trim($sort, "''");
                $name = "'" . $sort . "'";
                if ($name !== $createSort) {
                    $fieldsGenerateSortable[] = $name;
                }
            }
        }
        foreach ($updateFields as $index => $update) {
            //columns
            if ($update['show']) {
                $fieldsGenerateColumns[] = "'" . $update['field_name'] . "'";
            }
            if ($index === count($updateFields) - 1) {
                if ((\Str::contains($templateColumns, self::CREATE_AT))) {
                    $fieldsGenerateColumns[] = "'" . self::CREATE_AT . "'";
                }
                if ((\Str::contains($templateColumns, self::ACTIONS))) {
                    $fieldsGenerateColumns[] = "'" . self::ACTIONS . "'";
                }
            }
            //headings
            if ($update['show']) {
                $fieldsGenerateHeadings[] = "'" . $update['field_name'] . "'" . ': () => this.$t("table.' . $this->serviceGenerator->tableNameNotPlural($model['name']) . '.' . $update['field_name'] . '")' . ',';
            }
            if ($index === count($updateFields) - 1 && \Str::contains($templateHeadings, self::CREATE_AT)) {
                $fieldsGenerateHeadings[] = $createHeading;
            }
            //columnsClasses
            if ($update['show']) {
                switch ($update['db_type']) {
                    case 'Increments':
                    case $this->dbType['integer']:
                    case $this->dbType['bigInteger']:
                    case $this->dbType['float']:
                    case $this->dbType['double']:
                    case $this->dbType['boolean']:
                    case $this->dbType['date']:
                    case $this->dbType['dateTime']:
                    case $this->dbType['time']:
                    case $this->dbType['year']:
                    case $this->dbType['enum']:
                    case $this->dbType['file']:
                        $fieldsGenerateClasses[] = "'" . $update['field_name'] . "': 'text-center',";
                        break;
                }
            }
            if ($index === count($updateFields) - 1 && \Str::contains($templateClasses, self::CREATE_AT)) {
                $fieldsGenerateClasses[] = $createClasses;
            }
            //sortable
            if ($update['sort']) {
                $fieldsGenerateSortable[] = "'" . $update['field_name'] . "'";
            }
            if ($index === count($updateFields) - 1 && \Str::contains($templateSortable, self::CREATE_AT)) {
                $fieldsGenerateSortable[] = $createSort;
            }

            //replace template index.view
            if ($update['db_type'] === $this->dbType['longtext']) {
                if ($update['show']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['templates'], $this->generateHandler($update), 3, $templateDataReal);
                }
            }
            if ($update['db_type'] === $this->dbType['file']) {
                if ($update['show']) {
                    $templateDataReal = $this->serviceGenerator->replaceNotDelete($this->notDelete['templates'], $this->generateHandler($update), 3, $templateDataReal);
                }
            }
        }
        $templateDataReal = str_replace("$selfColumns: [" . $templateColumns . "]", "$selfColumns: [" . implode(', ', $fieldsGenerateColumns) . "]", $templateDataReal);
        $templateDataReal = str_replace("$selfHeadings: {" . $templateHeadings
            . "},", "$selfHeadings: {" . $this->replaceTemplate($fieldsGenerateHeadings, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfClasses: {" . $templateClasses
            . "},", "$selfClasses: {" . $this->replaceTemplate($fieldsGenerateClasses, 3) . "},", $templateDataReal);
        $templateDataReal = str_replace("$selfSortable: [" . $templateColumns . "]", "$selfSortable: [" . implode(', ', $fieldsGenerateSortable) . "]", $templateDataReal);
        return $templateDataReal;
    }

    private function generateHandler($field)
    {
        $fieldsGenerate = [];
        $pathTemplate = 'Handler/';
        $templateDataLongText = $this->serviceGenerator->get_template("longText", $pathTemplate, 'vuejs');
        $templateDataUploadParse = $this->serviceGenerator->get_template("uploadParse", $pathTemplate, 'vuejs');
        $templateBoolean = $this->serviceGenerator->get_template("boolean", $pathTemplate, 'vuejs');

        if ($field['db_type'] === $this->dbType['longtext']) {
            $fieldsGenerate[] = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateDataLongText);
        } else if ($field['db_type'] === $this->dbType['file']) {
            $fieldsGenerate[] = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateDataUploadParse);
        } else if ($field['db_type'] === $this->dbType['boolean']) {
            $fieldsGenerate[] = str_replace('{{$FIELD_NAME$}}', $field['field_name'], $templateBoolean);
        }

        return implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate);
    }

    private function replaceTemplate($fieldsGenerate, $tab)
    {
        return $this->serviceGenerator->infy_nl_tab(1, $tab) . implode($this->serviceGenerator->infy_nl_tab(1, 3), $fieldsGenerate) . $this->serviceGenerator->infy_nl_tab(1, $tab);
    }

    private function templateArray($templates, $fields)
    {
        $fieldsGenerate = [];
        foreach ($templates as $template) {
            if (\Str::contains($template, $fields['field_name_old']['field_name'])) {
                $fieldsGenerate[] = str_replace($fields['field_name_old']['field_name'], $fields['field_name_new']['field_name'], $template);
            } else {
                $fieldsGenerate[] = $template;
            }
        }
        return $fieldsGenerate;
    }

}
