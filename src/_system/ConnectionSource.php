<?php

namespace markorm\_system;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Container\Container;



abstract class ConnectionSource implements PDOAgent {

    final function __construct()
    {
        $capsule = new Capsule;

        $capsule->addConnection($this->getConnection());

        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

}