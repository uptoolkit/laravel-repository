<?php

namespace Blok\Repository\Criterias;

use Blok\Repository\AbstractCriteria;
use Blok\Repository\Traits\FormaterTrait;

class WhereCriteria extends AbstractCriteria
{
    use FormaterTrait;

    public function __construct($where)
    {
        $this->where = $where;
    }

    public function apply($model, $repository = null)
    {
        $filters = explode(' && ', $this->where);
        foreach ($filters as $filter) {
            $filteredModel = $model->where(function ($query) use ($filter) {
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
