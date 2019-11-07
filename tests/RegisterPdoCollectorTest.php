<?php

namespace Anper\PdoCollector\Tests;

use Anper\PdoCollector\Collector;
use Anper\PdoCollector\TraceableStatement;
use PHPUnit\Framework\TestCase;
use function Anper\PdoCollector\register_pdo_collector;

class RegisterPdoCollectorTest extends TestCase
{
    /**
     * @var \PDO
     */
    protected $pdo;

    protected function setUp()
    {
        $this->pdo = new \PDO(\getenv('DB_DSN'), \getenv('DB_USER'), \getenv('DB_PASSWD'));
    }

    protected function tearDown()
    {
        $this->pdo = null;
    }

    public function testRegisterCustomCollector(): void
    {
        $collector = function () {};

        register_pdo_collector($this->pdo, $collector);

        $this->assertCollector($collector);
    }

    public function testRegisterFromCollector(): void
    {
        $collector = new Collector($this->pdo);

        $this->assertCollector($collector);
    }

    /**
     * @param $collector
     */
    protected function assertCollector($collector): void
    {
        $attr = $this->pdo->getAttribute(\PDO::ATTR_STATEMENT_CLASS);

        $this->assertIsArray($attr);
        $this->assertEquals($attr[0], TraceableStatement::class);
        $this->assertIsArray($attr[1]);
        $this->assertEquals($attr[1][0], $collector);
    }
}
