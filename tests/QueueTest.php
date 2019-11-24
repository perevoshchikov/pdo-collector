<?php

namespace Anper\Pdo\StatementCollector\Tests;

use Anper\Pdo\StatementCollector\Profile;
use Anper\Pdo\StatementCollector\Queue;
use PHPUnit\Framework\TestCase;

/**
 * Class QueueTest
 * @package Anper\Pdo\StatementCollector\Tests
 */
class QueueTest extends TestCase
{
    /**
     * @var string
     */
    protected $connection = '1234567890';

    protected function tearDown(): void
    {
        parent::tearDown();

        Queue::clear($this->connection);
    }

    public function testPush(): void
    {
        $collector1 = function ($a) {};
        $collector2 = function ($b) {};

        Queue::push($this->connection, $collector1);

        $this->assertSame([$collector1], Queue::get($this->connection));

        Queue::push($this->connection, $collector2);

        $this->assertSame([$collector1, $collector2], Queue::get($this->connection));
    }

    public function testUnshift(): void
    {
        $collector1 = function ($a) {};
        $collector2 = function ($b) {};

        Queue::unshift($this->connection, $collector1);

        $this->assertSame([$collector1], Queue::get($this->connection));

        Queue::unshift($this->connection, $collector2);

        $this->assertSame([$collector2, $collector1], Queue::get($this->connection));
    }

    public function testRemove(): void
    {
        $collector1 = function ($a) {};
        $collector2 = function ($b) {};
        $collector3 = function ($c) {};

        Queue::push($this->connection, $collector1);
        Queue::push($this->connection, $collector2);

        $this->assertSame([$collector1, $collector2], Queue::get($this->connection));

        $result = Queue::remove($this->connection, $collector2);

        $this->assertTrue($result);
        $this->assertSame([$collector1], Queue::get($this->connection));

        $result = Queue::remove($this->connection, $collector3);

        $this->assertFalse($result);
    }

    public function testCollect(): void
    {
        $profile = $this->createMock(Profile::class);

        $givenProfile = null;

        $collector = function ($profile) use (&$givenProfile) {
            $givenProfile = $profile;
        };

        Queue::push($this->connection, $collector);
        Queue::collect($this->connection, $profile);

        $this->assertEquals($profile, $givenProfile);
    }
}
