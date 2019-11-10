<?php

namespace Anper\PdoCollector\Tests;

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
        $this->pdo = new \PDO(\getenv('DB_DSN'), \getenv('DB_USER'), \getenv('DB_PASSWD'));
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}
