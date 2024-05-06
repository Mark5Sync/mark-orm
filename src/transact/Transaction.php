<?php

namespace markorm\transact;

use markorm\_markers\transact;
use marksync\provider\NotMark;

#[NotMark]
class Transaction
{
    use transact;

    private $isActive = false;
    private $rollbackOnDestruct = true;
    function __construct(private \PDO $pdo)
    {
        if (!$this->transactionController->isActive) {
            $this->pdo->beginTransaction();
            $this->setActive(true);
        }
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
        if ($this->isActive)
            $this->pdo->rollBack();

        $this->setActive(false);
    }

    function commit()
    {
        $this->rollbackOnDestruct = false;
        if ($this->isActive)
            $this->pdo->commit();

        $this->setActive(false);
    }



    private function setActive(bool $status)
    {
        if ($this->isActive == $status)
            return;

        $this->isActive = $status;
        $this->transactionController->isActive = $status;
    }
}
