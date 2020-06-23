<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/Utils/Constants.php';
require __DIR__ . '/app/Config/config.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

set_time_limit(0);
ini_set('post_max_size', $details['POST_MAX_SIZE']);
ini_set('upload_max_filesize', $details['UPLOAD_MAX_FILESIZE']);

$isDevMode = false;

if ($details['APP_DEBUG']) {
    umask(0000);

    ini_set('display_errors', '1');
    error_reporting(E_ALL);

    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
}

$paths = [
    __DIR__. '/app/Entity'
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

function get_value_in_bytes($value){
    $attr_value = trim($value);

    if ($attr_value !== '') {
        $type_byte = strtolower($attr_value[strlen($attr_value) - 1]);
    } else {
        return $attr_value;
    }

    $v = (int) substr($attr_value, 0, -1);

    switch ($type_byte) {
        case 'g': $v *= 1024*1024*1024; break;
        case 'm': $v *= 1024*1024; break;
        case 'k': $v *= 1024; break;
    }
    return $v;
}

global $entityManager;
global $maxUploadSize, $maxPostSize;

$maxPostSize = ini_get('post_max_size');
$maxUploadSize = ini_get('upload_max_filesize');

$entityManager = EntityManager::create($details['db'], $config);

if (!defined('STDIN')) {
    require __DIR__ . '/bootstrap.php';
}
