<?php

namespace Anper\Pdo\StatementCollector;

/**
 * @param \PDO $pdo
 * @param callable $collector
 * @param bool $throw
 * @param bool $prepend
 *
 * @return bool
 * @throws Exception
 */
function register_pdo_collector(
    \PDO $pdo,
    callable $collector,
    bool $throw = true,
    bool $prepend = false
): bool {
    $connection = \spl_object_hash($pdo);

    if ($prepend) {
        Queue::unshift($connection, $collector);
    } else {
        Queue::push($connection, $collector);
    }

    $collect = static function ($profile) use ($connection) {
        Queue::collect($connection, $profile);
    };

    $result = $pdo->setAttribute(
        \PDO::ATTR_STATEMENT_CLASS,
        [Statement::class, [$collect]]
    );

    if ($result === false && $throw) {
        throw new Exception('Failed to register pdo collector.');
    }

    return $result;
}

/**
 * @param \PDO $pdo
 * @param callable $collector
 *
 * @return bool
 */
function unregister_pdo_collector(\PDO $pdo, callable $collector): bool
{
    return Queue::remove(\spl_object_hash($pdo), $collector);
}
