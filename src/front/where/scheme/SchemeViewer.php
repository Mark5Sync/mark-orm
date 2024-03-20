<?php

namespace markorm\front\where\scheme;

use markorm\front\where\WhereOption;

class SchemeViewer
{
    public string $table = 'some table';

    private $autoBuild = [];
    private $changedOptions = [];


    function setOption(string $option, $value)
    {
        $this->changedOptions[$option] = true;
        $this->autoBuild[] = $option;
        $this->{$option} = new WhereOption($option, $value, $this->table);
    }

    function setOptionProp(string $option, string $prop, $value)
    {
        $this->changedOptions[$prop] = true;
        $this->{$prop} = new WhereOption($option, $value, $this->table);
    }

    function reset()
    {
        foreach ($this->changedOptions as $prop => $_) {
            $this->{$prop} = null;
        }

        $this->changedOptions = [];
        $this->autoBuild = [];
    }

    function getSheme()
    {
        $result = [];
        foreach ($this->autoBuild as $option) {
            $result[] = $this->{$option}->expression;
        }

        return implode(' AND ', $result);
    }
}
