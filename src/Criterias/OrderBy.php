<?php

namespace Blok\Repository\Criterias;

use Blok\Repository\AbstractCriteria;

class OrderBy extends AbstractCriteria
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var string
     */
    private $direction;

    public function __construct($column = 'updated_at', $direction = null){

        if ($column && !$direction && str_contains($column, "-")) {
            list($column, $direction) = explode("-", $column);
        }

        if (!$column) {
            $column = "updated_at";
        }

        $this->column = $column;
        $this->direction = $direction ?? 'desc';
    }

    /**
     * @param $model \Eloquent
     * @param null $repository
     * @return mixed
     */
    public function apply($model, $repository = null)
    {
        return $model->orderBy($this->column, $this->direction);
    }
}
