<?php

namespace Anper\PdoCollector;

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
     */
    public function __construct(\PDO $pdo)
    {
        register_pdo_collector($pdo, $this);
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
    public function __invoke(Profile $profile)
    {
        $this->profiles[] = $profile;
    }
}
