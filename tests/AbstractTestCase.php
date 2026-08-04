<?php

declare(strict_types=1);

namespace DoctrineMongoODMModuleTest;

use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\WriteConcern;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected mixed $serviceManager;

    protected function setUp(): void
    {
        $this->serviceManager = ServiceManagerFactory::getServiceManager();
    }

    public function getDocumentManager(): DocumentManager
    {
        return $this->serviceManager->get('doctrine.documentmanager.odm_default');
    }

    protected function tearDown(): void
    {
        try {
            $connection   = $this->getDocumentManager()->getClient();
            $database     = $connection->selectDatabase('doctrineMongoODMModuleTest');
            $collections  = $database->listCollections();
            $writeConcern = new WriteConcern(1);     foreach ($collections as $collection) {
                $database->dropCollection($collection->getName(), ['writeConcern' => $writeConcern]);
            }
        } catch (RuntimeException) {
        }
    }
}
