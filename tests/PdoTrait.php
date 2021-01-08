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
            \sprintf('mysql:host=%s;port=%d', \getenv('DB_HOST'), \getenv('DB_PORT')),
            \getenv('DB_USER'),
            \getenv('DB_PASSWD')
        );

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);

        clear_collectors($this->pdo);
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}
