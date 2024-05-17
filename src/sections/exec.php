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

        if ($result)
            $result = array_map(fn ($row) => $this->cascadeSplit($row), $result);

        return $result;
    }




    private function cascadeSplit(array $data)
    {

        $nullableEssences = [];


        $result = [];

        foreach ($data as $key => $value) {
            if (str_starts_with($key, '__cascadeJoinField__')) {
                if (is_null($value))
                    $nullableEssences[] = substr($key, 20);
                continue;
            }

            $breadcrubs = explode('/', $key);

            $to = &$result;
            foreach ($breadcrubs as $breadcrub) {
                if (!isset($to[$breadcrub]))
                    $to[$breadcrub] = [];

                $to = &$to[$breadcrub];
            }

            $to = $value;
        }

        if (!empty($nullableEssences)) {
            foreach ($nullableEssences as $nullableKey) {
                unset($result[$nullableKey]); // = null;
            }
        }

        return $result;
    }


    private function exec()
    {
        $props = $this->sqlBuilder->getProps();
        $sql = $this->sqlBuilder->getSQL();

        $this->sql = $this->replace_props($sql, $props);

        $this->queryLogs->log($this->sql);

        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($props);

        return $stmt;
    }
}
