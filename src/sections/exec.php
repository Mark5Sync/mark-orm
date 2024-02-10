<?php

namespace markorm\sections;

use markorm\_markers\log;

trait exec
{
    use log;

    public function apply(): bool
    {
        return !!$this->exec();
    }


    public function fetch()
    {
        $stmt = $this->exec();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($cascadeResult = $this->applyCascadeMerge([$result]))
            $result = $cascadeResult[0];

        if ($result)
            $result = $this->cascadeSplit($result);

        return $result;
    }


    public function fetchAll()
    {
        $stmt = $this->exec();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        if ($cascadeResult = $this->applyCascadeMerge($result))
            $result = $cascadeResult;

        return $result;
    }




    private function cascadeSplit(array $data)
    {

        $result = [];
        foreach ($data as $key => $value) {
            $breadcrubs = explode('/', $key);

            $to = &$result;
            foreach ($breadcrubs as $breadcrub) {
                if (!isset($to[$breadcrub]))
                    $to[$breadcrub] = [];

                $to = &$to[$breadcrub];
            }

            $to = $value;
        }

        return $result;
    }


    private function exec()
    {
        $props = $this->sqlBuilder->getProps();
        $sql = $this->sqlBuilder->getSQL();

        $this->queryLogs->log($this->replace_props($sql, $props));

        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($props);

        return $stmt;
    }
}
