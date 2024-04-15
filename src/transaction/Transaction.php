<?php

namespace markorm\transaction;

class Transaction {

    private $rollbackOnDestruct = true;
    function __construct(private \PDO $pdo)
    {
        $this->pdo->beginTransaction();
    }

    function __destruct()
    {
        if ($this->rollbackOnDestruct)
            $this->rollBack();
    }

    function start()
    {
        return $this;
    }

    function rollBack()
    {
        $this->rollbackOnDestruct = false;
        $this->pdo->rollBack();
    }

    function commit()
    {
        $this->rollbackOnDestruct = false;
        $this->pdo->commit();
    }

}