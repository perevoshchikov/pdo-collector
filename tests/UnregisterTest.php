<?php

namespace Anper\Pdo\StatementCollector\Tests;

use Anper\Pdo\StatementCollector\Statement;
use PHPUnit\Framework\TestCase;

use function Anper\Pdo\StatementCollector\get_collectors;
use function Anper\Pdo\StatementCollector\register_collector;
use function Anper\Pdo\StatementCollector\unregister_collector;

class UnregisterTest extends TestCase
{
    use PdoTrait {
        setUp as pdoSetUp;
    }
    use CollectorsTrait;

    protected function setUp(): void
    {
        $this->setCollectors();
        $this->pdoSetUp();
    }

    public function testUnregister(): void
    {
        register_collector($this->pdo, $this->collectors[0]);
        register_collector($this->pdo, $this->collectors[1]);

        unregister_collector($this->pdo, $this->collectors[0]);

        self::assertNotContains($this->collectors[0], get_collectors($this->pdo));
        self::assertContains($this->collectors[1], get_collectors($this->pdo));
    }

    public function testUnregisterWithRestoreStatement(): void
    {
        register_collector($this->pdo, $this->collectors[0]);

        self::assertContains($this->collectors[0], get_collectors($this->pdo));
        $this->assertStatement(Statement::class);

        unregister_collector($this->pdo, $this->collectors[0]);

        self::assertEmpty(get_collectors($this->pdo));
        self::assertNotContains($this->collectors[0], get_collectors($this->pdo));
        $this->assertStatement(\PDOStatement::class);
    }

    protected function assertStatement(string $class): void
    {
        $attr = $this->pdo->getAttribute(\PDO::ATTR_STATEMENT_CLASS);

        self::assertIsArray($attr);
        self::assertEquals($attr[0], $class);
    }
}
