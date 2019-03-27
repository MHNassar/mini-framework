<?php

namespace Core\Database\Connection;

use PDO;
use PDOException;
use Core\Config;

class Connection implements IConnection
{
    private $_connection;
    private static $_instance;
    private $_host;
    private $_username;
    private $_password;
    private $_database;
    private $driver;

    private function __construct()
    {
        $this->_host = Config::get('database.db_host', "localhost");
        $this->_database = Config::get('database.db_name', "localhost");
        $this->_username = Config::get('database.db_admin', "localhost");
        $this->_password = Config::get('database.db_password', "localhost");

        $this->driver = "mysql:host=$this->_host;dbname=$this->_database";


        try {
            $this->_connection = new PDO($this->driver, $this->_username,
                $this->_password);
            // set the PDO error mode to exception
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get an instance of the Database
     * and connection
     */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function getConnection()
    {
        return $this->_connection;
    }

}
