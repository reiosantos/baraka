<?php

namespace App;

use App\Controllers\Mapper;
use App\Database\Database;
use App\Utils\Request;

global $database;
$database = new Database();
$request = new Request();

try {
    $controller = Mapper::getInstance()->getController($request->getControllerName());
    $data = $controller->processRequest($request);
} catch (\Exception $e) {
    $error = $e->getMessage();
}
