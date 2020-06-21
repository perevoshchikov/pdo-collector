<?php

namespace Anper\Pdo\StatementCollector;

use function Anper\CallableAggregate\aggregator;
use function Anper\CallableAggregate\clear_callbacks;
use function Anper\CallableAggregate\get_callbacks;
use function Anper\CallableAggregate\unregister_callback;

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
    $collection = aggregator($pdo);

    $result = $pdo->setAttribute(
        \PDO::ATTR_STATEMENT_CLASS,
        [Statement::class, [$collection]]
    );

    if ($result === false && $throw) {
        throw new Exception('Failed to register pdo collector.');
    }

    if ($result) {
        $prepend
            ? $collection->prepend($collector)
            : $collection->append($collector);
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
    return unregister_callback($pdo, $collector);
}

/**
 * @param \PDO $pdo
 *
 * @return array|callable[]
 */
function get_pdo_collectors(\PDO $pdo): array
{
    return get_callbacks($pdo);
}

/**
 * @param \PDO $pdo
 *
 * @return int
 */
function clear_pdo_collectors(\PDO $pdo): int
{
    return clear_callbacks($pdo);
}
