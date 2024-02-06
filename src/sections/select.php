<?php

namespace markorm\sections;

trait select {

    public function ___select(array $props)
    {
        $this->sqlBuilder->select($props);
    }

    protected function ___sel($props)
    {
        $colls = [];
        foreach ($props as $coll => $pass) {
            if (!$pass)
                continue;

            $colls[] = $coll;
        }
        $this->sqlBuilder->select($colls);
    }

    protected function ___selectAs($props)
    {
        $colls = [];
        foreach ($props as $coll => $collAs) {
            if (!$collAs)
                continue;

            $colls[$coll] = $collAs;
        }
        $this->sqlBuilder->select($colls, true);
    }

}