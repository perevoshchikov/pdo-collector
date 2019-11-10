<?php

namespace Anper\Pdo\StatementCollector;

/**
 * @param \PDO $pdo
 * @param callable $collector
 */
function register_pdo_collector(\PDO $pdo, callable $collector)
{
    $attr = $pdo->getAttribute(\PDO::ATTR_STATEMENT_CLASS);

    if (\is_array($attr) && isset($attr[1])) {
        $args = (array) $attr[1];
        $prevCollector = $args[0] ?? null;

        if ($prevCollector instanceof AggregateCollector) {
            $collector = $prevCollector->addCollector($collector);
        } else {
            $collector = new AggregateCollector([$prevCollector, $collector]);
        }
    }

    $pdo->setAttribute(
        \PDO::ATTR_STATEMENT_CLASS,
        [TraceableStatement::class, [$collector]]
    );
}
