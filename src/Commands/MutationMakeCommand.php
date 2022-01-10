<?php

namespace Blok\Repository\Commands;

use Blok\Repository\Mutations\AbstractCreateMutation;
use Blok\Repository\Mutations\AbstractDeleteMutation;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Blok\Repository\Mutations\AbstractUpdateMutation;

class MutationMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:mutation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new graphql mutation based on a Repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mutation';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/mutation.stub';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        if ($this->option('repository')) {
            $repository = $this->option('repository');
        } else {
            $repositoryName = "\\App\\Repositories\\" . str_replace([
                    "Mutation",
                    "Update",
                    "Create",
                    "Delete"
            ], "", $class) . 'Repository';

            $repository = $this->ask("From which repository do you want to create the repository ?", $repositoryName);
        }

        if (false !== stripos($name, "Update")) {
            $mutation = AbstractUpdateMutation::class;
        } elseif (false !== stripos($name, "Delete")) {
            $mutation = AbstractDeleteMutation::class;
        } else {
            $mutation = AbstractCreateMutation::class;
        }

        return str_replace(['DummyClass', 'DummyRepository', "DummyMutation"], [$class, $repository, $mutation], $stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\GraphQL\\Mutations';
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'name', InputArgument::REQUIRED, 'The name of the mutation'
            ],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['repository', 'r', InputOption::VALUE_OPTIONAL, 'The base repository of this mutation.'],
        ];
    }
}
