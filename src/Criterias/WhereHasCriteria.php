<?php

namespace Blok\Repository\Criterias;

use Blok\Repository\AbstractCriteria;
use Blok\Repository\Traits\FormatterTrait;
use Illuminate\Validation\UnauthorizedException;

/**
 * @warning Only use it on 100% public entities
 */
class WhereHasCriteria extends AbstractCriteria
{
    use FormatterTrait;

    public function __construct($whereHas, array $allowedColumns = null)
    {
        $this->where = $whereHas;
        $this->allowedColumns = $allowedColumns;
    }

    public function apply($model, $repository = null)
    {
        $filters = explode(' && ', $this->where);

        $filteredModel = null;

        foreach ($filters as $filter) {
            [$table, $filter] = explode('->', $filter);
            $filteredModel = $model->whereHas($table, function ($query) use ($filter) {
                $arrayFilters  = explode(' || ', $filter);
                foreach ($arrayFilters as $key => $arrayFilter) {
                    $arrayFilter    = explode(' ', $arrayFilter);
                    $filterFormated = $this->filterFormatter($arrayFilter);

                    if ($this->allowedColumns && !in_array($this->allowedColumns, $filterFormated['column'], true)) {
                        throw new UnauthorizedException();
                    }

                    $column         = $filterFormated['column'];
                    $operator       = $filterFormated['operator'];
                    $value          = $filterFormated['value'];
                    if ($key === 0) {
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
