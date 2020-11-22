<?php

namespace Anper\Pdo\StatementCollector\Tests;

use function Anper\Pdo\StatementCollector\clear_collectors;

/**
 * Trait PdoTrait
 * @package Anper\PdoCollector\Tests
 */
trait PdoTrait
{
    /**
     * @var \PDO
     */
    protected $pdo;

    protected function setUp(): void
    {
        $this->pdo = new \PDO(
            \getenv('DB_DSN'),
            \getenv('DB_USER'),
            \getenv('DB_PASSWD')
        );

        clear_collectors($this->pdo);
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}
