<?php

class DB{
    private $host = 'db';
    private $user = 'root';
    private $pass = 'root';
    private $dbname = 'slim';

    private static $instance = null;
    /**
     * @var mysqli
     */
    private $conn;

    /**
     * @return DB
     */
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getConnection(){
        return self::getInstance()->conn;
    }

    public function __construct(){
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if($this->conn->connect_error){
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}
