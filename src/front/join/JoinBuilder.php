<?php

namespace markorm\front\join;

use markdi\Mark;
use markorm\Model;


#[Mark(mode: Mark::LOCAL)]
class JoinBuilder
{

    private $tableName = false;
    private $joins = [];

    function push(Model $model, string $references, string $fields)
    {
        $this->joins[$model->table] = [
            'fields' => $fields,
            'model' => $model,
            'references' => $references,
        ];
    }

    function setTableName($name)
    {
        $this->tableName = $name;
    }

    function toSQL(){
        if (empty($this->joins))
            return '';

        $result = [];

        foreach ($this->joins as ['model' => $model,'references' => $references, 'fields' => $fields]) {
            $modelJoin = $model->sqlBuilder->joinBuilder->toSQL();
            $result[] = "
                LEFT JOIN {$model->table}
                ON {$model->table}.{$references} = {$this->tableName}.{$fields}
                $modelJoin
            ";
        }

        
        return implode("\n", $result);
    }

}