<?php

namespace markorm\tools;

use markorm\_system\Connection;

class ConnectionRecipient {

    function getConnectionData($model) {
        $reflection = new \ReflectionClass($model);
        $pdoAgents = $reflection->getAttributes(Connection::class);

        if (empty($pdoAgents))
            throw new \Exception('PDOAgint for obtaining connection was not found [' . get_class($model) . ']', 1);
        
        $connectionClass = $pdoAgents[0]->newInstance();
        $agent = new $connectionClass->pdoAgent;

        return [$agent->getConnection(), $connectionClass->table];
    }

}