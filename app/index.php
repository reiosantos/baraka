<?php

namespace App;

use App\Controllers\Mapper;
use App\Utils\Request;

$request = new Request();

try {
    $controller = Mapper::getInstance()->getController($request->getAction());
    $data = $controller->processRequest($request);
} catch (\Exception $e) {
    $error = $e->getMessage();
}
