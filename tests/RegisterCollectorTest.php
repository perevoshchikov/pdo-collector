<?php

namespace Anper\PdoCollector\Tests;

use Anper\PdoCollector\Collector;
use Anper\PdoCollector\TraceableStatement;
use PHPUnit\Framework\TestCase;

use function Anper\PdoCollector\register_pdo_collector;

/**
 * Class RegisterPdoCollectorTest
 * @package Anper\PdoCollector\Tests
 */
class RegisterCollectorTest extends TestCase
{
    use PdoTrait;

    public function testRegisterCustomCollector(): void
    {
        $collector = function () {
        };

        register_pdo_collector($this->pdo, $collector);

        $this->assertCollector($collector);
    }

    public function testRegisterCollector(): void
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
