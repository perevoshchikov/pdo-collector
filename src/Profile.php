<?php

namespace Anper\PdoCollector;

/**
 * Class Profile
 * @package Anper\PdoCollector
 */
class Profile
{
    /**
     * @var string
     */
    protected $sql;

    /**
     * @var int
     */
    protected $rowCount = 0;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var float
     */
    protected $duration = 0;

    /**
     * @var int int
     */
    protected $memoryUsage = 0;

    /**
     * @var \Exception|null
     */
    protected $exception;

    /**
     * @param string $sql
     * @param array $params
     * @param int $rowCount
     * @param float $duration
     * @param int $memoryUsage
     * @param \Exception|null $e
     */
    public function __construct(
        string $sql,
        array $params = [],
        int $rowCount = 0,
        float $duration = 0,
        int $memoryUsage = 0,
        \Exception $e = null
    ) {
        $this->sql = $sql;
        $this->rowCount = $rowCount;
        $this->parameters = $params;
        $this->duration = $duration;
        $this->memoryUsage = $memoryUsage;
        $this->exception = $e;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getMemoryUsage(): int
    {
        return $this->memoryUsage;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->exception === null;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->exception !== null;
    }

    /**
     * @return \Exception|null
     */
    public function getException(): ?\Exception
    {
        return $this->exception;
    }

    /**
     * @return string
     */
    public function getSqlWithParams(): string
    {
        $sql = $this->getSql();

        foreach ($this->getParameters() as $key => $value) {
            $value = $this->formatParam($value);

            if (\is_numeric($key)) {
                $pos = \strpos($sql, '?');
                $sql = \substr($sql, 0, $pos)
                    . $value
                    . substr($sql, $pos + 1);
            } else {
                $sql = \str_replace($key, $value, $sql);
            }
        }

        return $sql;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function formatParam($value): string
    {
        switch (\gettype($value)) {
            case 'integer':
            case 'double':
                return (string) $value;
            case 'boolean':
                return $value ? 'true' : 'false';
            case 'null':
                return 'null';
            default:
                return \sprintf('"%s"', \addslashes($value));
        }
    }
}
