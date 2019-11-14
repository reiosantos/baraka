<?php

use App\Controllers\Mapper;
use App\Database\Database;
use App\Utils\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$APP_DIR = '/app';
$APP_PATH = __DIR__ . $APP_DIR;

$loader = new FilesystemLoader([
    $APP_PATH . '/static/templates',
    $APP_PATH . '/static/templates/partials'
]);

$params = [
    'cache' => './cache',
    'auto_reload' => true,
    'debug' => true,
    'autoescape' => 'html',
    'strict_variables' => true
];

global $twig;
$twig = new Environment($loader, $params);

$twig->addGlobal('base_path', $APP_DIR);
$twig->addGlobal('js_path', $APP_DIR . '/static/js/');
$twig->addGlobal('css_path', $APP_DIR . '/static/css/');

global $database;
$database = new Database();
$request = new Request();

$twig->addGlobal('current_route', $request->getControllerName());

try {
    $controller = Mapper::getInstance()->getController($request->getControllerName());
    $controller->processRequest($request);
} catch (RuntimeException $e) {
    $twig->display('error.html.twig', ['error' => $e->getMessage()]);
}
