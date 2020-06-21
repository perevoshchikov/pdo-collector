<?php

namespace Anper\Pdo\StatementCollector;

/**
 * Class Collector
 * @package Anper\PdoCollector
 */
class Collector
{
    /**
     * @var Profile[]
     */
    protected $profiles = [];

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @param \PDO $pdo
     *
     * @throws Exception
     */
    public function __construct(\PDO $pdo)
    {
        register_collector($pdo, $this, true);

        $this->pdo = $pdo;
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    /**
     * @return Profile[]
     */
    public function getProfiles(): array
    {
        return $this->profiles;
    }

    /**
     * @param Profile $profile
     */
    public function __invoke(Profile $profile): void
    {
        $this->profiles[] = $profile;
    }
}
