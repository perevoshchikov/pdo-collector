<?php

namespace Anper\PdoCollector;

use PDO;

/**
 * Class Statement
 * @package Anper\PdoCollector
 */
class TraceableStatement extends \PDOStatement
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var callable
     */
    protected $collector;

    /**
     * @param callable $collector
     */
    protected function __construct(callable $collector)
    {
        $this->collector = $collector;
    }

    /**
     * @inheritDoc
     */
    public function bindColumn(
        $column,
        &$param,
        $type = null,
        $maxlen = null,
        $driverdata = null
    ) {
        $this->params[$column] = $param;

        parent::bindColumn($column, $param, $type, $maxlen, $driverdata);
    }

    /**
     * @inheritDoc
     */
    public function bindParam(
        $parameter,
        &$variable,
        $data_type = PDO::PARAM_STR,
        $length = null,
        $driver_options = null
    ) {
        $this->params[$parameter] = $variable;

        parent::bindParam($parameter, $variable, $data_type, $length, $driver_options);
    }

    /**
     * @inheritDoc
     */
    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
    {
        $this->params[$parameter] = $value;

        parent::bindValue($parameter, $value, $data_type);
    }

    /**
     * @inheritDoc
     */
    public function execute($input_parameters = null)
    {
        if (\is_array($input_parameters)) {
            foreach ($input_parameters as $key => $value) {
                $this->params[$key] = $value;
            }
        }

        $start = \microtime(true);

        try {
            $result = parent::execute($input_parameters);
        } catch (\PDOException $e) {
            $PDOexception = $e;
        }

        $end = \microtime(true);
        $memoryUsage = \memory_get_usage();

        if (isset($result) && $result === false) {
            if ($error = $this->errorInfo()) {
                $errorException = new \PDOException($error[2], 0);
            }
        }

        $profile = new Profile(
            $this->queryString,
            $this->params,
            $this->rowCount(),
            $end - $start,
            $memoryUsage,
            $PDOexception ?? $errorException ?? null
        );

        \call_user_func($this->collector, $profile);

        if (isset($PDOexception)) {
            throw $PDOexception;
        }

        return $result ?? false;
    }
}
