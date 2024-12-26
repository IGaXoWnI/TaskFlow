<?php
class database{
    private $dbname = "task";
    private $user = "postgres";
    private $password = "070911";
    private $host = "localhost";
    private $port = "5432";

  public function connect(){
    try{
        $pdo = new PDO("pgsql:host=$this->host;dbname=$this->dbname;port=$this->port" , $this->user , $this->password) ;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }catch(PDOException $e){
      die("the error is " . $e->getMessage());
    }
  }



}


?>