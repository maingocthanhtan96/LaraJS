<?php

namespace App\Service;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class QueryService extends BaseService
{

    protected $_model;

    public function __construct($model)
    {
        $this->_model = $model;
    }

    public function queryTable($columns = [], $search = '', $columnSearch = [] , $with = [], $limit = 25, $ascending = 0, $orderBy = 'created_at', $defaultOrderBy = 'created_at', $defaultDecending = 'desc')
    {
        $ascending = $ascending == 0 ? 'asc' : 'desc';

        $query = $this->_model::query();
        if(count(Arr::wrap($with)) > 0){
            $query->with($with);
        }

        foreach(Arr::wrap($columns) as $value => $col) {
            $query->when($value === $orderBy, function($q) use ($col, $ascending) {
                $q->orderBy($col, $ascending);
            });
        }

        $query->when($search, function($q) use ($search, $columnSearch){
            $q->whereLike($columnSearch, $search);
        });

        $query = $query->orderBy($defaultOrderBy, $defaultDecending);

        return $query->paginate($limit)->toArray();
    }
}
