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

    if ($result) {
        if ($prepend) {
            Queue::unshift($connection, $collector);
        } else {
            Queue::push($connection, $collector);
        }
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

/**
 * @param \PDO $pdo
 *
 * @return array|callable[]
 */
function get_pdo_collectors(\PDO $pdo): array
{
    return Queue::get(\spl_object_hash($pdo));
}

/**
 * @param \PDO $pdo
 *
 * @return bool
 */
function clear_pdo_collectors(\PDO $pdo): bool
{
    return Queue::clear(\spl_object_hash($pdo));
}
