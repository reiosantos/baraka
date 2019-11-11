<?php
/**
 * Created by PhpStorm.
 * User: reiosantos
 * Date: 1/6/18
 * Time: 3:28 PM
 */

namespace SAN\Database;


class Connection
{
    private $username = null;
    private $pass = null;

    private $dsn = null;
    private $conn = null;
    private static $instance = null;

    /**
     * Private Connection constructor.
     */
    private function __construct()
    {
        global $details;
        $dts = $details['db'];

        $this->username = $dts['username'];
        $this->pass = $dts['password'];
        $this->dsn = 'mysql:host=' . $dts['host'] . ';dbname=' .  $dts['database'];
    }

    public static function getConnection(): \PDO
    {
        if (self::$instance === null) {
            self::$instance = new Connection();
        }
        return self::$instance->connect();
    }

    private function connect(): \PDO
    {
        if ($this->conn) {
            return $this->conn;
        }
        $this->conn = new \PDO($this->dsn, $this->username, $this->pass);
        return $this->conn;
    }
}
