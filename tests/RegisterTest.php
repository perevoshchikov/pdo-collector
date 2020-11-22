<?php

namespace Anper\Pdo\StatementCollector\Tests;

use Anper\Pdo\StatementCollector\Exception;
use Anper\Pdo\StatementCollector\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function Anper\Pdo\StatementCollector\clear_collectors;
use function Anper\Pdo\StatementCollector\get_collectors;
use function Anper\Pdo\StatementCollector\register_collector;

/**
 * Class RegisterPdoCollectorTest
 * @package Anper\PdoCollector\Tests
 */
class RegisterTest extends TestCase
{
    use PdoTrait {
        setUp as parentSetUp;
    }

    /**
     * @var array
     */
    protected $collectors = [];

    protected function setUp(): void
    {
        $this->parentSetUp();

        clear_collectors($this->pdo);

        $this->collectors = [
            function ($a) {
                //
            },
            function ($b) {
                //
            },
        ];
    }

    public function testRegister(): void
    {
        $result1 = register_collector($this->pdo, $this->collectors[0]);
        $result2 = register_collector($this->pdo, $this->collectors[1]);

        self::assertTrue($result1);
        self::assertTrue($result2);
        $this->assertStatement();

        self::assertSame($this->collectors, get_collectors($this->pdo));
    }

    public function testPrependRegister(): void
    {
        register_collector($this->pdo, $this->collectors[0]);
        register_collector($this->pdo, $this->collectors[1], true, true);

        self::assertSame([$this->collectors[1], $this->collectors[0]], get_collectors($this->pdo));
    }

    public function testRegisterWithException(): void
    {
        $this->expectException(Exception::class);

        register_collector($this->failedPdo(), $this->collectors[0], true);
    }

    public function testFailedRegister(): void
    {
        $pdo = $this->failedPdo();

        $result = register_collector($pdo, $this->collectors[0], false);

        self::assertNotContains($this->collectors[0], get_collectors($pdo));
        self::assertFalse($result);
    }

    /**
     * @return \PDO|MockObject
     */
    protected function failedPdo()
    {
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('setAttribute')
            ->willReturn(false);

        return $pdo;
    }

    protected function assertStatement(): void
    {
        $attr = $this->pdo->getAttribute(\PDO::ATTR_STATEMENT_CLASS);

        self::assertIsArray($attr);
        self::assertEquals($attr[0], Statement::class);
        self::assertIsArray($attr[1]);
        self::assertIsCallable($attr[1][0]);
    }
}
