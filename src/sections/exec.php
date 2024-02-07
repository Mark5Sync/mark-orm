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

        if (!$this->useJoinCascade)
            return $result;

        return $this->cascadeSplit($result);
    }

    public function fetchAll()
    {
        $stmt = $this->exec();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$this->useJoinCascade)
            return $result;

        return array_map(fn ($row) => $this->cascadeSplit($row), $result);
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
