<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

set_time_limit(0);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/Config/config.php';

$isDevMode = false;

if ($details['APP_DEBUG']) {
    umask(0000);

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
}

$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__. '/app'), $isDevMode);
// or if you prefer XML
// $config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config"), $isDevMode);
// database configuration parameters

global $entityManager;

$entityManager = EntityManager::create($details['db'], $config);

require_once __DIR__ . '/app.php';
