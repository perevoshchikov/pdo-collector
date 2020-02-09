<?php

namespace Anper\Pdo\StatementCollector;

use Anper\CallableAggregate\CallableAggregate;
use Anper\CallableAggregate\CallableAggregateInterface;

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
    $collection = pdo_collector_collection($pdo);

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
    $collection = pdo_collector_collection($pdo);

    if ($has = $collection->has($collector)) {
        $collection->remove($collector);
    }

    return $has;
}

/**
 * @param \PDO $pdo
 *
 * @return array|callable[]
 */
function get_pdo_collectors(\PDO $pdo): array
{
    return pdo_collector_collection($pdo)->all();
}

/**
 * @param \PDO $pdo
 *
 * @return bool
 */
function clear_pdo_collectors(\PDO $pdo): bool
{
    $collection = pdo_collector_collection($pdo);

    if ($count = $collection->count()) {
        $collection->clear();
    }

    return $count > 0;
}

/**
 * @param \PDO $pdo
 *
 * @return CallableAggregateInterface
 */
function pdo_collector_collection(\PDO $pdo): CallableAggregateInterface
{
    static $collection = null;

    if ($collection === null) {
        $collection = new \SplObjectStorage();
    }

    if (isset($collection[$pdo])) {
        return $collection[$pdo];
    }

    return $collection[$pdo] = new CallableAggregate();
}
