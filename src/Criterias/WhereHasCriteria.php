<?php

namespace Blok\Repository\Criterias;

use Blok\Repository\AbstractCriteria;
use Blok\Repository\Traits\FormaterTrait;

class WhereHasCriteria extends AbstractCriteria
{
    use FormaterTrait;

    public function __construct($where_has)
    {
        $this->where = $where_has;
    }

    public function apply($model, $repository = null)
    {
        $filters = explode(' && ', $this->where);
        foreach ($filters as $filter) {
            $table_filter  = explode('->', $filter);
            $table         = $table_filter[0];
            $filter        = $table_filter[1];
            $filteredModel = $model->whereHas($table, function ($query) use ($filter) {
                $arrayFilters  = explode(' || ', $filter);
                foreach ($arrayFilters as $key => $arrayFilter) {
                    $arrayFilter    = explode(' ', $arrayFilter);
                    $filterFormated = $this->filterFormater($arrayFilter);
                    $column         = $filterFormated['column'];
                    $operator       = $filterFormated['operator'];
                    $value          = $filterFormated['value'];
                    if ($key == 0) {
                        $query->where($column, $operator, $value);
                    } else {
                        $query->orWhere($column, $operator, $value);
                    }
                }
            });
        }
        return $filteredModel;
    }
}
