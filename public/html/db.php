<?php
require_once('config/config.php');

class Database{

    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    private $connection;
    private $error;
    private $stmt;
    private $dbconnected=false;

    // Database Driver: Spawns persistent PDO connection streams using database constants
    function __construct(){
        $dsn='mysql:host='.$this->host.";dbname=".$this->dbname;
        $option=array(
            PDO::ATTR_PERSISTENT=>true,
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        );
        try{
            $this->connection=new PDO($dsn, 
            $this->user,
            $this->password,
            $option);
            $this->dbconnected=true;
        }catch(PDOException $e){
            $this->error = $e->getMessage();
            $this->dbconnected=false;
        }
    }

    function getError(){ return $this->error; }
    function isConnected(){ return $this->dbconnected; }

    function query($query){
        $this->stmt=$this->connection->prepare($query);
    }

    function execute(){
        return $this->stmt->execute();
    }

    // Query Method: Returns all matching entities map-transformed into clean objects
    function set(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Query Method: Restricts and returns exactly one mapped object entry row
    function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Parameter Sanitizer: Explicit type evaluation mapping to protect from SQL injection
    function bind($param, $value, $type=null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type=PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type= PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type=PDO::PARAM_NULL;
                    break;
                default:
                    $type=PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param,$value,$type);
    }
}
?>