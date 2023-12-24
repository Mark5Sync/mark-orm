<?php

namespace markorm\front\where;

use markorm\_markers\front;



class WhereBuilder
{
    use front;

    private $options = [];
    private $tableName = false;

    function push($option, $props){
        $this->options[] = $this->whereOption($option, $props);
    }

    function reset(){
        $this->options = [];
    }
    
    function setTableName($name){
        $this->tableName = $name;
    }

    function getWhere(): ?string
    {
        if (empty($this->options))
            return null;

        $result = [];
        foreach ($this->options as $option) {
            $result[] = $option->toSQL();
        }

        return implode(' AND ', $result);
    }

}
