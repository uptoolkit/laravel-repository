<?php

namespace Blok\Repository\Mutations;

use Blok\Repository\Contracts\HasRepositoryInterface;
use Blok\Repository\Contracts\RepositoryContract;

abstract class AbstractUpdateMutation implements HasRepositoryInterface
{
    public RepositoryContract $repository;

    public string $key = 'id';

    public function __construct()
    {
        $repository = $this->repository();
        $this->repository = new $repository();
    }

    /**
     * You should declare the RepositoryClass or compatible RepositoryContract class
     *
     * @return string
     */
    abstract public function repository():string;

    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        return $this->repository->update(\Arr::except($args, [$this->key]), $args[$this->key]);
    }
}
