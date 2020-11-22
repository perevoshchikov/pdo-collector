<?php

namespace Anper\Pdo\StatementCollector\Tests;

trait CollectorsTrait
{
    /**
     * @var array
     */
    protected $collectors = [];

    protected function setCollectors(): void
    {
        $this->collectors = [
            function ($a) {
                //
            },
            function ($b) {
                //
            },
        ];
    }
}
