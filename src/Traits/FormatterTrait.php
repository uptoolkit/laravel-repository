<?php

namespace Blok\Repository\Traits;

use JetBrains\PhpStorm\ArrayShape;

trait FormatterTrait
{
    #[ArrayShape(['column' => "string", 'operator' => "string", 'value' => "string"])]
    public function filterFormatter(array $arrayFilter): array
    {
        [$column, $operator, $value] = $arrayFilter;

        if ($value === 'null') {
            $value = null;
        }

        if ($operator === '===') {
            $operator = '=';
        } else if ($operator === '==') {
            $operator = '=';
        }

        return ['column' => $column, 'operator' => $operator, 'value' => $value];
    }
}
