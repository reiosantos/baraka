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

global $database;
global $twig;

$database = new Database();
$request = new Request();
$twig = new Environment($loader, $params);

$is_admin_url = strpos($request->getBaseUrl(), '/admin') === 0;

$twig->addGlobal('base_path', $APP_DIR);
$twig->addGlobal('base_url', $request->getBaseUrl());
$twig->addGlobal('js_path', $APP_DIR . '/static/js/');
$twig->addGlobal('css_path', $APP_DIR . '/static/css/');
$twig->addGlobal('request', $request);
$twig->addGlobal('is_admin_url', $is_admin_url);
$twig->addGlobal('is_authenticated', $request->isAuthenticated());

$twig->addGlobal('current_route', $request->getControllerName());

try {
    $controller = Mapper::getInstance()->getController($request->getControllerName());
    $controller->processRequest($request);
} catch (RuntimeException $e) {
    $twig->display('error.html.twig', ['error' => $e->getMessage()]);
}
