<?php

namespace App\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabasePrimer
{
    public static function prime(KernelInterface $kernel)
    {
        // Check our environment
        if ($kernel->getEnvironment() !== 'test') {
            throw new \LogicException("This database primer should only be executed in a test environment", 1);
        }

        // Get the database entity manager
        $entityManager = $kernel->getContainer()->get("doctrine.orm.entity_manager");

        // Set up the test database
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadata);
    }
}
