<?php

namespace markorm\sections;

trait exec
{

    public function apply(): bool
    {
        return !!$this->exec();
    }

    public function fetch()
    {
        $stmt = $this->exec();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($this->joinArray))
            $result = $this->cascadeArrayMerge([$result])[0];

        if ($this->useJoinCascade)
            $result = $this->cascadeSplit($result);

        return $result;
    }

    public function fetchAll()
    {
        $stmt = $this->exec();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $splitConcade = $this->useJoinCascade;

        if (!empty($this->joinArray))
            $result = $this->cascadeArrayMerge($result);

        if ($splitConcade)
            $result = array_map(fn ($row) => $this->cascadeSplit($row), $result);

        return $result;
    }


    private function cascadeArrayMerge(array $data){
        $joinArray = $this->joinArray;
        $this->joinArray = [];
        $this->useJoinCascade = false;

        foreach ($joinArray as $joinAs => $joinCascadeArray) {
            $joinCascadeArray->merge($data, $this);
        }

        return $data;
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

        $this->sql = $this->replace_props($sql, $props);

        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($props);

        return $stmt;
    }
}
