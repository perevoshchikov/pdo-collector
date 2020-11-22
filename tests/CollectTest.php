<?php

namespace Anper\Pdo\StatementCollector\Tests;

use Anper\Pdo\StatementCollector\Collector;
use Anper\Pdo\StatementCollector\Profile;
use PHPUnit\Framework\TestCase;

use function Anper\Pdo\StatementCollector\register_collector;

/**
 * Class CollectTest
 * @package Anper\PdoCollector\Tests
 */
class CollectTest extends TestCase
{
    use PdoTrait;

    /**
     * @return array
     */
    public function collectorProvider(): array
    {
        return [
            [function (\PDO $pdo, string $sql, array $params) {
                $profile = null;
                register_collector($pdo, function ($p) use (&$profile) {
                    $profile = $p;
                });

                $sth = $pdo->prepare($sql);
                $sth->execute($params);

                return $profile;
            }],
            [function (\PDO $pdo, string $sql, array $params) {
                register_collector($pdo, $collector = new Collector());

                $sth = $pdo->prepare($sql);
                $sth->execute($params);

                $profiles = $collector->getProfiles();

                return \array_shift($profiles);
            }],
        ];
    }

    /**
     * @dataProvider collectorProvider
     * @param callable $collector
     */
    public function testCollect(callable $collector): void
    {
        $sql = 'select 1,2,?';
        $params = [3];

        /** @var Profile $profile */
        $profile = $collector($this->pdo, $sql, $params);

        $this->assertNotNull($profile);
        $this->assertEquals($profile->getSql(), $sql);
        $this->assertSame($profile->getRowCount(), 1);
        $this->assertTrue($profile->getMemoryUsage() > 0);
        $this->assertTrue($profile->getDuration() > 0);
        $this->assertTrue($profile->isSuccess());
        $this->assertFalse($profile->isError());
        $this->assertNull($profile->getException());
        $this->assertSame($profile->getParameters(), $params);
        $this->assertSame($profile->getSqlWithParameters(), 'select 1,2,3');
    }

    /**
     * @dataProvider collectorProvider
     * @param callable $collector
     */
    public function testCollectWithException(callable $collector): void
    {
        $sql = 'select 1,2,? from';
        $params = [3];

        /** @var Profile $profile */
        $profile = $collector($this->pdo, $sql, $params);

        $this->assertNotNull($profile);
        $this->assertEquals($profile->getSql(), $sql);
        $this->assertSame($profile->getRowCount(), 0);
        $this->assertTrue($profile->getMemoryUsage() > 0);
        $this->assertTrue($profile->getDuration() > 0);
        $this->assertFalse($profile->isSuccess());
        $this->assertTrue($profile->isError());
        $this->assertNotNull($profile->getException());
        $this->assertSame($profile->getParameters(), $params);
        $this->assertSame($profile->getSqlWithParameters(), 'select 1,2,3 from');
    }
}
