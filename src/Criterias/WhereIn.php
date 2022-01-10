<?php


namespace Blok\Repository\Criterias;

use Blok\Repository\AbstractCriteria;

class WhereIn extends AbstractCriteria
{
    /**
     * @var array
     */
    private $ids;
    /**
     * @var string
     */
    private $column;

    public function __construct($ids = [], $column = 'id'){

        $this->ids = $ids;
        $this->column = $column;
    }

    public function apply($model, $repository = null)
    {
        return $model->whereIn($this->column, $this->ids);
    }
}
