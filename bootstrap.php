<?php

use App\Controllers\Mapper;
use App\Database\Database;
use App\Utils\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

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

$twig->addGlobal('base_path', $APP_DIR);
$twig->addGlobal('js_path', $APP_DIR . '/static/js/');
$twig->addGlobal('css_path', $APP_DIR . '/static/css/');

$twig->addFunction(
    new TwigFunction(
        'form_token',
        static function () {
            global $request;

            if (empty($request->getFromSession('token'))) {
                $request->addToSession('token', bin2hex(random_bytes(32)));
            }
            return $request->getFromSession('token');
        }
    )
);

$twig->addGlobal('current_route', $request->getControllerName());

try {
    $controller = Mapper::getInstance()->getController($request->getControllerName());
    $controller->processRequest($request);
} catch (RuntimeException $e) {
    $twig->display('error.html.twig', ['error' => $e->getMessage()]);
}
