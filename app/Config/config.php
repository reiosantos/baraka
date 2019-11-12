<?php
/**
 * Created by PhpStorm.
 * User: reiosantos
 * Date: 1/4/18
 * Time: 9:50 PM
 */

global $details;
$details=array();

/**
 * Update the following
 * values to match your database
 * configurations
 */

$details['db'] = [
    'driver' => 'pdo_mysql',
    'host' => getenv('DB_HOST'),
    'user' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'dbname' => getenv('DB_DATABASE')
];

$details['APP_DEBUG'] = getenv('APP_DEBUG');
