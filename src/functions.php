<?php

namespace Anper\PdoCollector;

use Anper\PdoCollector\TraceableStatement;

/**
 * @param \PDO $pdo
 * @param callable $collector
 */
function register_pdo_collector(\PDO $pdo, callable $collector)
{
    $pdo->setAttribute(
        \PDO::ATTR_STATEMENT_CLASS,
        [TraceableStatement::class, [$collector]]
    );
}
