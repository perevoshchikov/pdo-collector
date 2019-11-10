<?php

namespace Anper\Pdo\StatementCollector;

/**
 * Class AggregateCollector
 * @package Anper\Pdo\StatementCollector
 */
class AggregateCollector
{
    /**
     * @var callable[]
     */
    protected $collectors = [];

    /**
     * @param callable[] $collectors
     */
    public function __construct(array $collectors = [])
    {
        $this->collectors = \array_filter($collectors, function ($collector) {
            return \is_callable($collector);
        });
    }

    /**
     * @param callable $collector
     *
     * @return $this
     */
    public function addCollector(callable $collector): self
    {
        $this->collectors[] = $collector;

        return $this;
    }

    /**
     * @param Profile $profile
     */
    public function __invoke(Profile $profile)
    {
        foreach ($this->collectors as $collector) {
            $collector($profile);
        }
    }
}
