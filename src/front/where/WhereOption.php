<?php

namespace markorm\front\where;

use marksync\provider\MarkInstance;


#[MarkInstance('whereOption')]
class WhereOption
{

    function __construct(
        private string $option,
        private $props,
        private string | false $tableName = false
    ) {
    }


    private function arrayColl($coll)
    {
        if (!is_array($coll['value']))
            return ':' . $coll['dataColl'];

        $result = [];
        foreach ($coll['value'] as $key => $_) {
            $result[] = ":{$coll['dataColl']}_$key";
        }

        return implode(',', $result);
    }


    function toSQL(): ?string
    {
        $result = [];

        switch ($this->option) {
            case 'where':
                foreach ($this->props as $coll) {
                    $option = is_null($coll['value']) ? 'IS' : '=';
                    $result[] = "{$this->tableName}.$coll[coll] $option :$coll[dataColl]";
                }
                break;
            case 'like':
                foreach ($this->props as $coll) {
                    $result[] = "{$this->tableName}.$coll[coll] LIKE :$coll[dataColl]";
                }
                break;
            case 'in':
            case 'notIn':
                $isNot = $this->option == 'notIn';
                $notOption = $isNot ? 'NOT' : '';
            
                foreach ($this->props as $coll) {
                    $arrayColl = $this->arrayColl($coll);

                    if ($isNot && empty($arrayColl))
                        continue;

                    $result[] = "{$this->tableName}.$coll[coll] {$notOption} IN ($arrayColl)";
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

            case 'isNull':
            case 'isNotNull':
                $operator = $this->option == 'isNull' ? " IS NULL " : " IS NOT NULL ";
                foreach ($this->props as $coll) {
                    $result[] = "{$this->tableName}.$coll[coll] $operator";
                }
                break;
        }

        if (empty($result))
            return null;

        return implode(' AND ', $result);
    }


}
