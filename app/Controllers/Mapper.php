<?php
namespace App\Controllers;

use RuntimeException;

class Mapper
{
    private $controllerMap;
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): Mapper {
        if (self::$instance === null) {
            self::$instance = new Mapper();
        }

        $glob = glob(__DIR__ . '/*Controller.php', GLOB_BRACE);
        foreach ($glob as $index => $path) {
            if(!preg_match('/[a-zA-Z]Controller.php/', $path)) {
                unset($glob[$index]);
                continue;
            }
            $splitPath = explode('/', $path);
            $filename = end($splitPath);
            $splitFileName = explode('.php', $filename);
            $controller = __NAMESPACE__ . '\\' . $splitFileName[0];

            $name = explode('Controller.php', $filename);
            $name = strtolower($name[0]);

            self::$instance->controllerMap[$name] = new $controller();
        }

        return self::$instance;
    }

    /**
     * @param string $name
     * @return Controller
     * @throws RuntimeException
     */
    public function getController(?string $name): Controller {
        if (!array_key_exists($name, $this->controllerMap)) {
            throw new RuntimeException('No handler found to process this request.');
        }
        return $this->controllerMap[$name];
    }
}
