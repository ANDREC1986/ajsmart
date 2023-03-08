<?php

namespace App\Utils;
use PDO;
use PDOException;

class DB{
private $table;
private $connection;
private $database;
private $host;

    public function __construct($table = null){
        $this->database = getenv('DB_NAME');
        $this->host = getenv('DB_LOCATION');
        $this->table = $table;
        $this->setConnection();
    }

    private function setConnection() {
        try {
            $dns = "mysql:host=$this->host;dbname=$this->database";
            $this->connection = new PDO($dns,getenv('DB_USER'),getenv('DB_PASS'));
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            die('ERROR!'.$e->getMessage());
        }       
    }

    private function execute($query,$params = []){
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        }
        catch(PDOException $e){
            die('ERROR!'.$e->getMessage());
        }    
    }
    public function post($values = []){
        $fields = array_keys($values);
        $values_count = array_pad([],count($fields),'?');

        $query = "INSERT INTO ".$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$values_count).")";
        $this->execute($query,array_values($values));
    }

    public function get($where = null, $order = null, $limit = null, $fields = '*', $object = null) {
        $where = strlen($where) ? "WHERE ".$where : '';
        $order = strlen($order) ? "ORDER BY ".$order : '';
        $limit = strlen($limit) ? "LIMIT ".$limit : '';
        $query = "SELECT $fields from $this->table ".$where.$order.$limit;
        $result = $this->execute($query);
        if($object == null) {
        return $result->fetchAll(PDO::FETCH_CLASS);
        } elseif ($object != null) {
            return $result->fetchAll(PDO::FETCH_CLASS,$object);
        }
    }

}