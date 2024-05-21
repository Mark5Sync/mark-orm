<?php

namespace markorm\model;

use marksync\provider\Mark;

#[Mark(mode: Mark::LOCAL)]
class Request
{

    private $stack = [];


    function set(string $key, $value)
    {
        return $this->stack[$key] = $value;
    }


    function get(string $key, $default = [])
    {
        if (!isset($this->stack[$key]))
            return $default;

        return $this->stack[$key];
    }


    function filter($props, $except): array
    {
        $result = [];

        foreach ($props as $key => $value) {
            if ($value != $except)
                $result[$key] = $value;
        }

        return $result;
    }
}
