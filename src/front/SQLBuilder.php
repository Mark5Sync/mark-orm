<?php

namespace markorm\front;

use markdi\Mark;
use markorm\_markers\front;
use markorm\Model;

#[Mark('sqlBuilder', mode: Mark::LOCAL)]
class SQLBuilder
{
    use front;


    private string $description = '';
    private string $table;

    private $request = [];
    private $propsValues = [];
    private $selected = [];


    function join(Model $model, string $references, string $fields)
    {
        $this->joinBuilder->push(
            $model,
            $references,
            $fields,
        );
    }

    function setTable(string $table)
    {
        $this->table = $table;
        $this->whereBuilder->setTableName($table);
        $this->joinBuilder->setTableName($table);
    }

    function select(array $colls, bool $as = false)
    {
        $key = $as ? 'selectAs' : 'select';
        $sel = isset($this->selected[$key]) ? $this->selected[$key] : [];
        $result = [...$sel, ...$colls];
        $this->selected[$key] = $as ? $result : array_unique($result);
    }

    function push($option, $props, $add = false)
    {
        $props = $this->filter($option, $props);
        $this->request[$option] = $add
            ? [...$this->request[$option], $props]
            : $props;

        if (is_array($props))
            $this->pushToPropsValues($props);
    }


    function pushWhere($option, $props)
    {
        $props = $this->filter($option, $props);
        $this->whereBuilder->push($option, $props);

        $this->pushToPropsValues($props);
    }

    private function filter($option, $values)
    {
        if (!is_array($values))
            return $values;


        $result = [];

        foreach ($values as $coll => $value) {
            if (is_null($value))
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

    private function pushToPropsValues(array $props)
    {
        foreach ($props as $prop) {
            $this->propsValues[$prop['dataColl']] = $prop['value'];
        }
    }




    function getSQL(): string
    {
        $result = null;
        $mode = isset($this->request['insert'])
            ? 'insert'
            : (isset($this->request['update'])
                ? 'update'
                : (isset($this->request['delete'])
                    ? 'delete'
                    : (isset($this->request['select'])
                        ? 'select'
                        : 'select'
                    )));

        switch ($mode) {
            case 'insert':
                $result = $this->getInsert();
                break;
            case 'update':
                $result = $this->getUpdate();
                break;
            case 'delete':
                $result = $this->getDelete();
                break;
            case 'select':
                $result = $this->getSelect();
                break;
            default:
                throw new \Exception("Что я делаю...", 1);
        }

        $this->reset();

        return "{$this->description}{$result}";
    }


    private function getInsert()
    {
        $props = $this->request['insert'];
        $collProps = implode(', ', array_column($props, 'coll'));
        $collValues = ':' . implode(', :', array_column($props, 'dataColl'));

        $sql = "INSERT INTO {$this->table}({$collProps}) VALUES({$collValues})";

        return $sql;
    }


    private function getUpdate()
    {
        $props = implode(', ', array_map(
            fn ($coll) => "$coll[coll] = :$coll[dataColl]",
            $this->request['update']
        ));

        $blockJoin  = $this->joinBuilder->toSQL();
        $blockWhere = $this->getBlockWhere();


        $sql = "UPDATE $this->table $blockJoin SET $props  $blockWhere";




        return $sql;
    }


    private function getDelete(): string
    {
        $blockWhere = $this->getBlockWhere();

        $result = "DELETE FROM {$this->table} {$blockWhere}";
        return $result;
    }




    private function getSelect()
    {
        $select     = $this->getSelectBlock();
        $blockJoin  = $this->joinBuilder->toSQL();
        $blockWhere = $this->getBlockWhere();

        $select = !empty($select) ? implode(', ', $select) : '*';

        $sql = "SELECT $select FROM $this->table $blockJoin $blockWhere";

        return $sql;
    }


    function getSelectBlock(): array
    {
        $select = [];

        foreach ($this->selected as $key => $colls) {
            foreach ($colls as $as => $coll) {
                $select[] = $key == 'selectAs'
                    ? "{$this->table}.$as as $coll"
                    : "{$this->table}.$coll";
            }
        }

        $select = [...$select, ...$this->joinBuilder->getSelect()];

        return $select;
    }


    private function getBlockWhere()
    {
        $result = '';
        if ($where = $this->whereMix())
            $result = " WHERE " . implode(' AND ', $where);

        return $result;
    }


    function whereMix()
    {
        $result = $this->whereBuilder->getWhere();

        $result = [...$result, ...$this->joinBuilder->getWhere()];

        return $result;
    }



    function getProps(): array | null
    {
        $props = [...$this->propsValues, ...$this->joinBuilder->getProps()];

        return $props;
    }


    function reset()
    {
        $this->request = [];
        $this->propsValues = [];
        $this->selected = [];
        $this->whereBuilder->reset();
        $this->joinBuilder->reset();
    }



    function __toString()
    {
        return $this->getSql();
    }


    function desc(string $description)
    {
        $this->description = "/* $description */\n";
    }
}
