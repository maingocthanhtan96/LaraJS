<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class QueryService extends BaseService
{
    const ASC = 'asc';
    const DESC = 'desc';
    const ASCENDING = 'ascending';
    /**
     * Eloquent model
     * @var Model $_model
     */
    protected Model $_model;

    /**
     * Select column owner
     * @var array
     */
    public array $select = [];

    /**
     * Column to search using whereLike
     * @var array
     */
    public array $columnSearch = [];
    /**
     * Relationship with other tables
     * @var array
     */
    public array $withRelationship = [];
    /**
     * Paragraph search in column
     * @var ?string
     */
    public ?string $search;
    /**
     * Start date - End date
     * @var array
     */
    public array $betweenDate = [];

    /**
     * ascending, descending
     * @var ?string
     */
    public ?string $ascending = '';
    /**
     * Column to order
     * @var string
     */
    public string $orderBy = 'updated_at';
    /**
     * Always order this column
     * @var string
     */
    public string $defaultUpdatedAt = 'updated_at';

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
     * @return mixed
     * @author tanmnt
     */
    public function queryTable()
    {
        $query = $this->_model::query();
        if ($this->ascending) {
            $this->ascending = $this->ascending === self::ASCENDING ? self::ASC : self::DESC;
        }
        $query->when($this->select, fn($q) => $q->select($this->select));
        $query->when($this->search, fn($q) => $q->whereLike($this->columnSearch, $this->search));
        foreach (Arr::wrap($this->withRelationship) as $relationship) {
            $query = $query->with($relationship);
        }
        $query->when(isset($this->betweenDate[0]) && isset($this->betweenDate[1]), function ($q) {
            $startDate = Carbon::parse($this->betweenDate[0])->startOfDay();
            $endDate = Carbon::parse($this->betweenDate[1])->endOfDay();
            $q->whereBetween($this->defaultUpdatedAt, [$startDate, $endDate]);
        });
        $query->when($this->orderBy && $this->ascending, fn($q) => $q->orderBy($this->orderBy, $this->ascending));

        return $query;
    }
}
