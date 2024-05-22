<?php

namespace markorm\model;

use markorm\Model;
use marksync\provider\Mark;

#[Mark(mode: Mark::LOCAL, args: ['parent'])]
class Request
{

    private $stack = [];


    function __construct(private Model $model)
    {
    }


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


    function filter($props, $except, mixed $set = false): array
    {
        $result = [];

        foreach ($props as $key => $value) {
            if ($value !== $except)
                $result[$key] = $set ? $set : $value;
        }

        return $result;
    }






    function build()
    {
        return $this->model->getModel();
    }
}
