<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/Config/config.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

set_time_limit(0);

$isDevMode = false;

if ($details['APP_DEBUG']) {
    umask(0000);

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
}

$paths = [
    __DIR__. '/app/Entity'
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

global $entityManager;

$entityManager = EntityManager::create($details['db'], $config);

require __DIR__ . '/bootstrap.php';
