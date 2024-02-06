<?php

namespace markorm\sections;

trait exec {

    

    public function apply(): bool
    {
        return !!$this->exec();
    }

    public function fetch()
    {
        $stmt = $this->exec();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        $stmt = $this->exec();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    private function exec()
    {
        $props = $this->sqlBuilder->getProps();
        $sql = $this->sqlBuilder->getSQL();

        $this->sql = $this->replace_props($sql, $props);

        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($props);

        return $stmt;
    }

}