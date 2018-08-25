<?php
/**
 * Created by PhpStorm.
 * User: reiosantos
 * Date: 1/6/18
 * Time: 3:28 PM
 */

require __DIR__ . "/config/config.php";


class Connection
{
    /**
     * DARE NOT TAMPER WITH THESE VARIABLES YOU FOOL!!
     */
    private $username = null;
    private $pass = null;
    private $dbname = null;
    private $host = null;

    private $dsn = null;
    private static $instance = null;

    /**
     * Connection constructor.
     */
    private function __construct()
    {
        global $details;

        $this->username = $details['username'];
        $this->pass = $details['password'];
        $this->dbname = $details['database'];
        $this->host = $details['host'];

        $this->dsn = "mysql:host=". $this->host .";dbname=". $this->dbname;
    }
    public static function getConnection(){
        try{
            self::$instance = new Connection();
            return new PDO(self::$instance->dsn, self::$instance->username, self::$instance->pass);

        }catch (PDOException $exception){
            echo json_encode(['error'=>$exception->getMessage()]);
            return null;
        }
    }

}
