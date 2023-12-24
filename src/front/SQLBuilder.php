<?php

namespace markorm\front;

use markdi\Mark;
use markorm\_markers\front;

#[Mark('sqlBuilder', mode: Mark::LOCAL)]
class SQLBuilder
{
    use front;

    private string $table;
    private $request = [];
    private $propsValues = [];

    private $select = [];

    function setTable(string $table)
    {
        $this->table = $table;
    }

    function select(array $colls, bool $as = false)
    {
        $this->request['select'] = [
            'option' => $as ? 'selectAs' : 'select',
            'colls' => $colls
        ];
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


    function reset()
    {
        $this->request = [];
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

        return $result;
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


        $blockWhere = $this->getBlockWhere();


        $sql = "UPDATE $this->table SET $props $blockWhere";




        return $sql;
    }


    private function getDelete(): string
    {
        $blockWhere = $this->getBlockWhere();

        $result = "DELETE FROM {$this->table} {$blockWhere}";
        return $result;
    }


    private function getBlockWhere(){
        $result = '';
        if ($where = $this->whereBuilder->getWhere())
            $result = " WHERE $where";

        return $result;
    }


    private function getSelect()
    {
        ['option' => $option, 'colls' => $colls] = isset($this->request['select'])
            ? $this->request['select']
            : ['option' => 'select', 'colls' => []];

        $select = [];
        foreach ($colls as $coll => $collAs) {
            $select[] = $option == 'selectAs'
                ? "$coll as $collAs"
                : "$collAs";
        }


        $blockWhere = $this->getBlockWhere();


        $select = implode(', ', $select);
        $select = $select ? $select : '*';


        $sql = "SELECT $select FROM $this->table $blockWhere";

        return $sql;
    }



    function getProps(): array | null
    {
        $props = [...$this->propsValues];

        $result = empty($props) ? null : $props;
        $this->propsValues = [];


        return $result;
    }


    function __toString()
    {
        return $this->getSql();
    }
}
