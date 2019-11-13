<?php

namespace App;

use App\Controllers\Mapper;
use App\Database\Database;
use App\Entity\Artist;
use App\Entity\Feedback;
use App\Entity\Song;
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

$artists = $database->getRepository(Artist::class)->findAll();
$songs = $database->getRepository(Song::class)->findAll();
$feedback = $database->getRepository(Feedback::class)->findAll();
