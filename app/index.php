<?php

namespace App;

use App\Controllers\Mapper;
use App\Utils\Request;


$controller= Mapper::getInstance()->get($_GET['name'] ?? NULL);

try {
    $request = new Request();

    $data = $controller->processRequest($request);
} catch (\Exception $e) {
    $error = $e->getMessage();
}
