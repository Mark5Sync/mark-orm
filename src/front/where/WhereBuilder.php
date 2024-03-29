<?php

namespace markorm\front\where;

use marksync\provider\Mark;
use markorm\_markers\front;

#[Mark(mode: Mark::LOCAL)]
class WhereBuilder
{
    use front;


    private $options = [];
    private $tableName = false;

    function push($option, $props){
        $this->options[] = $this->whereOption($option, $props, $this->tableName);
    }

    function reset(){
        $this->options = [];
    }
    
    function setTableName($name){
        $this->tableName = $name;
    }

    function getWhere(): array
    {
        $result = [];
        foreach ($this->options as $option) {
            if ($strOptions = $option->toSQL())
                $result[] = $strOptions;
        }


        
        return $result;
    }

}
