<?php

namespace markorm\model;

class Request
{


    function filter($props, $except, mixed $set = false): array
    {
        $result = [];

        foreach ($props as $key => $value) {
            if ($value !== $except)
                $result[$key] = $set ? $set : $value;
        }

        return $result;
    }
}
