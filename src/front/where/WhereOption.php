<?php

namespace markorm\front\where;

use markdi\MarkInstance;


#[MarkInstance('whereOption')]
class WhereOption
{

    function __construct(
        private string $option,
        private $props,
        private string | false $tableName = false
    ) {
    }


    function toSQL(): string
    {
        $result = [];

        switch ($this->option) {
            case 'where':
                foreach ($this->props as $coll) {
                    $result[] = "{$this->tableName}.$coll[coll] = :$coll[dataColl]";
                }
                break;
            case 'like':
                foreach ($this->props as $coll) {
                    $result[] = "{$this->tableName}.$coll[coll] LIKE :$coll[dataColl]";
                }
                break;
            case 'in':
                foreach ($this->props as $coll) {
                    $result[] = "{$this->tableName}.$coll[coll] IN (:$coll[dataColl])";
                }
                break;
            case 'regexp':
                foreach ($this->props as $coll) {
                    $result[] = "{$this->tableName}.$coll[coll] REGEXP :$coll[dataColl]";
                }
                break;
            case 'or':
                $result[] = 'or';
                break;
        }

        return implode(' AND ', $result);
    }

}
