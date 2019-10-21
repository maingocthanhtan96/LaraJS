<?php

namespace App\Service;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class QueryService extends BaseService
{
    protected $_model;

    /**
     * QueryService constructor.
     * @param $model
     * @author tanmnt
     */
    public function __construct($model)
    {
        $this->_model = $model;
    }

    /**
     * Query table
     * @param array $columns
     * @param array $columnsWith
     * @param string $search
     * @param array $columnSearch
     * @param array $with
     * @param int $limit
     * @param int $ascending
     * @param string $orderBy
     * @param string $defaultOrderBy
     * @param string $defaultDescending
     * @return mixed
     * @author tanmnt
     */
    public function queryTable($columns = [], $columnsWith = [], $search = '', $columnSearch = [], $with = [], $limit = 25, $ascending = 0, $orderBy = 'created_at', $defaultOrderBy = 'created_at', $defaultDescending = 'desc')
    {
        $ascending = $ascending == 0 ? 'asc' : 'desc';

        $query = $this->_model::query();
        if (count(Arr::wrap($with)) > 0) {
            $query = $query->with(Arr::wrap($with));
        }

        foreach (Arr::wrap($columns) as $col) {
            $query->when($col === $orderBy, function ($q) use ($col, $ascending) {
                $q->orderBy($col, $ascending);
            });
        }

        foreach (Arr::wrap($columnsWith) as $value => $col) {
            $query->when($value === $orderBy, function ($q) use ($col, $ascending) {
                $q->orderBy($col, $ascending);
            });
        }

        $query->when($search, function ($q) use ($search, $columnSearch) {
            $q->whereLike($columnSearch, $search);
        });

        $query = $query->orderBy($defaultOrderBy, $defaultDescending);

        return $query->paginate($limit)->toArray();
    }
}
