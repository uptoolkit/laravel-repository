<?php

namespace App\Repositories\Criterias;

use Blok\Repository\AbstractCriteria;

class OrderByDistance extends AbstractCriteria
{
    /**
     * @var
     */
    private $lat;
    /**
     * @var
     */
    private $lng;
    /**
     * @var string
     */
    private $direction;

    /**
     * SearchByQuery constructor.
     * @param $lat
     * @param $lng
     * @param string $direction
     * @internal param array $tags
     * @internal param $query
     */
    public function __construct($lat, $lng, $direction = "asc"){

        $this->lat = $lat;
        $this->lng = $lng;
        $this->direction = strtoupper($direction);
    }

    /**
     * @param $model \Eloquent
     * @param null $repository
     * @return mixed
     */
    public function apply($model, $repository = null)
    {
        return $model->join('addresses', function($join) use ($repository) {

            $join->on('addresses.model_id', '=',  $repository->getTable().'.id');
        })
            ->where('addresses.model_type', '=', $repository->model())
            ->where('addresses.role', '=', "main")
            ->selectRaw('ST_Distance_Sphere(POINT(lng, lat), POINT(' . $this->lng.", ".$this->lat . ')) AS distance, '.$repository->getTable().'.*, addresses.city')
            ->with('addresses')
            ->orderBy('distance', $this->direction);
    }
}
