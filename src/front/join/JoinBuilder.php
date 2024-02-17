<?php

namespace markorm\front\join;

use markdi\Mark;
use markorm\Model;


#[Mark(mode: Mark::LOCAL)]
class JoinBuilder
{

    private $tableName = false;
    private $joins = [];

    function push(Model $model, string $references, string $fields, string $type, ?string $joinAs)
    {
        $this->joins[$model->table] = [
            'fields' => $fields,
            'model' => $model,
            'references' => $references,
            'type' => $type,
            'joinAs' => $joinAs,
        ];
    }

    function setTableName($name)
    {
        $this->tableName = $name;
    }

    function toSQL()
    {
        if (empty($this->joins))
            return '';

        $result = [];

        foreach ($this->joins as ['model' => $model, 'references' => $references, 'fields' => $fields]) {
            $modelJoin = $model->sqlBuilder->joinBuilder->toSQL();
            $result[] = "
                LEFT JOIN {$model->table}
                ON {$model->table}.{$references} = {$this->tableName}.{$fields}
                $modelJoin
            ";
        }


        return implode("\n", $result);
    }

    function getProps()
    {
        $result = [];

        foreach ($this->joins as ['model' => $model]) {
            $result = [...$result, ...$model->sqlBuilder->getProps()];
        }

        return $result;
    }

    function getWhere()
    {
        $result = [];

        foreach ($this->joins as ['model' => $model]) {
            $result = [...$result, ...$model->sqlBuilder->whereMix()];
        }

        return $result;
    }

    function getSelect(?string $cascadeTitle = null)
    {
        $result = [];

        foreach ($this->joins as ['model' => $model, 'joinAs' => $joinAs]) {
            $joinKey = $cascadeTitle && $joinAs 
                            ? "$cascadeTitle/$joinAs"
                            : (
                                $cascadeTitle 
                                    ? $cascadeTitle
                                    : $joinAs
                            );
            $result = [...$result, ...$model->sqlBuilder->getSelectBlock($joinKey)];
        }

        return $result;
    }



    function reset(){
        foreach ($this->joins as $join) {
            $join['model']->sqlBuilder->reset();
        }
        $this->joins = [];
    }
}
