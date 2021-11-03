<?php

namespace Blok\Repository\Traits;

trait FormaterTrait
{
    public function filterFormater(array $arrayFilter)
    {
        $column      = $arrayFilter[0];
        $operator    = $arrayFilter[1];
        $value       = $arrayFilter[2];
        if ($value == 'null') {
            $value = null;
        }
        if (is_numeric($value)) {
            if (strpos($value, '.') !== false) {
                $value = (float)$value;
            } else {
                $value = (int)$value;
            }
        }
        if ($operator == '==') {
            $operator = '=';
        }
        $filterFormated = ['column' => $column, 'operator' => $operator, 'value' => $value];
        return $filterFormated;
    }
}
