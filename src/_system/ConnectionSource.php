<?php

namespace markorm\_system;

use Illuminate\Database\Capsule\Manager as Capsule;



abstract class ConnectionSource implements PDOAgent
{

    private $isConnected = false;

    function getPDO()
    {
        [
            'host' => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password,
        ] = $this->getConnection();

        return new \PDO(
            "mysql:host=$host;dbname=$database",
            $username,
            $password
        );
    }


    function createGlobalconnection()
    {
        if ($this->isConnected)
            return;

        $capsule = new Capsule;

        $capsule->addConnection($this->getConnection());

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
