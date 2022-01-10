<?php

namespace Blok\Repository\Criterias;

use Blok\Repository\AbstractCriteria;
use Blok\Repository\Traits\FormatterTrait;
use Illuminate\Validation\UnauthorizedException;

class WhereCriteria extends AbstractCriteria
{
    use FormatterTrait;

    public $where;

    /**
     * @var null
     */
    private $allowedColumns;

    /**
     * @param $where
     * @param array|null $allowedColumns
     */
    public function __construct($where, array $allowedColumns = null)
    {
        $this->where = $where;
        $this->allowedColumns = $allowedColumns;
    }

    public function apply($model, $repository = null)
    {
        $filters = explode(' && ', $this->where);

        $filteredModel = null;

        foreach ($filters as $filter) {
            $filteredModel = $model->where(function ($query) use ($filter) {
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
