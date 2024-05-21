<?php

namespace markorm\_system;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Container\Container;



abstract class ConnectionSource implements PDOAgent
{


    function getPDO()
    {
        [
            'host' => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password,
        ] = $this->getConnection();

        return new \PDO(
            "pgsql:host=$host;dbname=$database",
            $username,
            $password
        );
    }


    function createGlobalconnection()
    {
        $capsule = new Capsule;

        $capsule->addConnection($this->getConnection());

        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
