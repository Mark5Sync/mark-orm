<?php


namespace markorm\_system;


abstract class ConnectionSource implements PDOAgent {

    public \PDO $pdo;

    final function __construct(){
        $this->pdo = $this->getConnection();
    }

}