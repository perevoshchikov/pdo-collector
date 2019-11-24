<?php

namespace Anper\Pdo\StatementCollector;

/**
 * Class StaticQueue
 * @package Anper\Pdo\StatementCollector
 */
class Queue
{
    /**
     * @var callable[]
     */
    protected static $collectors = [];

    /**
     * @param string $connection
     * @param callable $collector
     */
    public static function push(string $connection, callable $collector): void
    {
        static::$collectors[$connection][] = $collector;
    }

    /**
     * @param string $connection
     * @param callable $collector
     */
    public static function unshift(string $connection, callable $collector): void
    {
        if (isset(static::$collectors[$connection]) === false) {
            static::$collectors[$connection] = [];
        }

        \array_unshift(static::$collectors[$connection], $collector);
    }

    /**
     * @param string $connection
     * @param callable $collector
     *
     * @return bool
     */
    public static function remove(string $connection, callable $collector): bool
    {
        if (isset(static::$collectors[$connection]) === false) {
            return false;
        }

        $pos = \array_search($collector, static::$collectors[$connection], true);

        if ($pos !== false) {
            unset(static::$collectors[$connection][$pos]);
        }

        return $pos !== false;
    }

    /**
     * @param string $connection
     * @param Profile $profile
     */
    public static function collect(string $connection, Profile $profile): void
    {
        foreach (static::get($connection) as $collector) {
            $collector($profile);
        }
    }

    /**
     * @param string $connection
     *
     * @return callable[]
     */
    public static function get(string $connection): array
    {
        return \array_values(static::$collectors[$connection] ?? []);
    }

    /**
     * @return callable[]
     */
    public static function all(): array
    {
        return static::$collectors;
    }

    /**
     * @param string $connection
     *
     * @return bool
     */
    public static function clear(string $connection): bool
    {
        if ($has = isset(static::$collectors[$connection])) {
            unset(static::$collectors[$connection]);
        }

        return $has;
    }
}
