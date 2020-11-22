<?php

namespace Anper\Pdo\StatementCollector;

/**
 * Class Collector
 * @package Anper\Pdo\StatementCollector
 */
class Collector
{
    /**
     * @var Profile[]
     */
    protected $profiles = [];

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
