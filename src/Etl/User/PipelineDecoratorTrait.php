<?php

namespace App\Etl\User;

use Kiboko\Component\ETL\Flow\Loader\LoaderInterface;
use Kiboko\Component\ETL\Flow\Transformer\TransformerInterface;
use Kiboko\Component\ETL\Pipeline;

trait PipelineDecoratorTrait
{
    /** @var Pipeline\PipelineInterface */
    private $decorated;

    public function transform(TransformerInterface $transformer): Pipeline\Feature\TransformingInterface
    {
        $this->decorated->transform($transformer);

        return $this;
    }

    public function load(LoaderInterface $loader): Pipeline\Feature\LoadingInterface
    {
        $this->decorated->load($loader);

        return $this;
    }

    /**
     * @return \Iterator
     */
    public function walk(): \Iterator
    {
        return $this->decorated->walk();
    }

    /**
     * @return int
     */
    public function run(): int
    {
        return $this->decorated->run();
    }
}
