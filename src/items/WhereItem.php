<?php

namespace markorm\items;

use marksync\provider\MarkInstance;



#[MarkInstance]
class WhereItem 
{
    public string $scheme;
    public array $props;
    private array $queryProps;
    private bool $exportProps = true;

    function __construct(private string $tableName, private $method, array $props, ?string $scheme = null, public $useProps = true)
    {
        $this->props = $this->filter($this->method, $props);
        $this->queryProps = $this->getQueryProps();
        $this->scheme = $scheme ? $this->handleScheme($scheme) : implode(' AND ', $this->queryProps);
    }


    private function handleScheme(string $scheme): string
    {
        $result = $scheme;

        foreach ($this->queryProps as $prop) {
            $result = preg_replace('/\?/', $prop, $result, 1);
        }

        $result = str_replace('@',  "{$this->tableName}.", $result);

        return $result;
    }


    function getQueryProps(): array
    {
        $result = [];

        switch ($this->method) {
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
                $isNot = $this->method == 'notIn';
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
                $operator = $this->method == 'isNull' ? " IS NULL " : " IS NOT NULL ";
                foreach ($this->props as $coll) {
                    $result[] = "{$this->tableName}.$coll[coll] $operator";
                }
                break;
        }

        return $result;
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


    private function filter($option, $values)
    {
        if (!is_array($values))
            return $values;

        $result = [];

        foreach ($values as $coll => $value) {
            if ($value === false)
                continue;

            $dataColl = "{$option}_{$coll}";

            if ($value == 'NULL') {
                $value = null;
            }

            $result[$dataColl] = [
                'coll' => $coll,
                'dataColl' => $dataColl,
                'value' => $value,
            ];
        }


        return $result;
    }


    function setExportProps(bool $status): self
    {
        $this->exportProps = $status;

        return $this;
    }

    function getExportProps(): bool
    {
        return $this->exportProps;
    }



    function __toString()
    {
        return $this->scheme;
    }
}

