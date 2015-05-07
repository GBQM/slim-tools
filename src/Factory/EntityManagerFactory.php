<?php
namespace Neochic\SlimTools\Factory;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    public static function createEntityManager(array $database, array $doctrine) {
        $isDevMode = true;

        $config = Setup::createAnnotationMetadataConfiguration($doctrine['model.paths'], $isDevMode, null, null, false);

        return EntityManager::create($database, $config);
    }
}