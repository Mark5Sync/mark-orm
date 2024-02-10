<?php

namespace markorm\sections;

use markorm\_markers\front;
use markorm\_markers\tools;

trait select {
    use front;

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



    protected function ___selectDate($props){
        $colls = [];
        $dateFormats = [];
        foreach ($props as $coll => $dateFormat) {
            if (!$dateFormat)
                continue;

            $dateFormats[$coll] = $dateFormat;
            $colls[] = $coll;
        }
        $this->sqlBuilder->select($colls);
    }

}