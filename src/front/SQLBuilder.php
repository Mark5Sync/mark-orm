<?php

namespace markorm\front;

use markdi\Mark;
use markorm\_markers\front;
use markorm\Model;
use markorm\tools\Page;
use PDO;

#[Mark('sqlBuilder', mode: Mark::LOCAL)]
class SQLBuilder
{
    use front;


    private string $description = '';
    private string $table;

    private $request = [];
    private $propsValues = [];
    private $setOptions = [];
    private $selected = [];

    public ?Model $parentModel = null;
    public ?Page  $page = null;
    public array $joinArray = [];

    private $orderByType = 'ASC';
    private $orderByColls = [];


    function setOrderBy($orderByType, $orderByColls)
    {
        $this->orderByType = $orderByType;
        $this->orderByColls = $orderByColls;
    }



    function join(Model $model, string $references, string $fields, string $type, ?string $joinAs)
    {
        $this->joinBuilder->push(
            $model,
            $references,
            $fields,
            $type,
            $joinAs,
        );
    }


    function joinCascadeArray(Model $model, string $references, string $fields, string $type, ?string $joinAs)
    {
        $this->joinCascadeArrayBuilder->push(
            $model,
            $references,
            $fields,
            $type,
            $joinAs,
        );
    }

    function getPDO()
    {
        return $this->parentModel->getPDO();
    }

    function setTable(string $table)
    {
        $this->table = $table;
        $this->whereBuilder->setTableName($table);
        $this->joinBuilder->setTableName($table);
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


    function set($option, $value)
    {
        $this->setOptions[$option] = $value;
    }


    function pushWhere($option, $props, $useProps = true)
    {
        $props = $this->filter($option, $props);
        $this->whereBuilder->push($option, $props);

        if ($useProps)
            $this->pushToPropsValues($props);
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
            : (isset($this->request['insertOnDublicateUpdate'])
                ? 'insertOnDublicateUpdate'
                : (isset($this->request['update'])
                    ? 'update'
                    : (isset($this->request['delete'])
                        ? 'delete'
                        : (isset($this->request['select'])
                            ? 'select'
                            : 'select'
                        ))));

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
            case 'insertOnDublicateUpdate':
                $result = $this->getInsertOnDublicateUpdate();
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


    private function getInsertOnDublicateUpdate()
    {
        $props = $this->request['insertOnDublicateUpdate'];
        $collProps = implode(', ', array_column($props, 'coll'));
        $collValues = ':' . implode(', :', array_column($props, 'dataColl'));


        $updateProps = implode(', ', array_map(
            fn ($coll) => "$coll[coll] = :$coll[dataColl]",
            $this->request['insertOnDublicateUpdate']
        ));


        $sql = "INSERT INTO {$this->table}({$collProps}) VALUES({$collValues}) ON DUPLICATE KEY UPDATE $updateProps";

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




    private function getSelect(bool $selectCount = false)
    {
        $select = $selectCount
            ? ['COUNT(*) as count']
            : $this->getSelectBlock();

        $blockJoin  = $this->joinBuilder->toSQL();
        $blockWhere = $this->getBlockWhere($selectCount);

        $select = !empty($select) ? implode(', ', $select) : '*';

        $sql = "SELECT $select FROM $this->table $blockJoin $blockWhere";

        return $sql;
    }


    function getSelectCount()
    {
        return $this->getSelect(true);
    }


    function getSelectBlock(?string $joinAs = null): array
    {
        $select = [];

        foreach ($this->selected as $key => $colls) {
            foreach ($colls as $as => $coll) {
                $as = $key == 'selectAs' ? $as : $coll;
                $joinCollName = $joinAs ? "$joinAs/$coll" : $coll;


                $select[] = $key == 'selectAs' || $joinAs
                    ? "{$this->table}.$as as `$joinCollName`"
                    : "{$this->table}.$coll";
            }
        }

        $select = [...$select, ...$this->joinBuilder->getSelect($joinAs)];

        return $select;
    }


    private function getBlockWhere($notUseLimits = false)
    {
        $result = '';
        if ($where = $this->whereMix())
            $result = " WHERE " . implode(' AND ', $where);


        if (!$notUseLimits && !empty($this->orderByColls)) {
            $orderColls = array_map(fn ($orderCollItem) => "{$this->parentModel->table}.{$orderCollItem}", $this->orderByColls);
            $result .= " ORDER BY " . implode(', ', $orderColls) . " $this->orderByType";
        }



        if (!$notUseLimits)
            if ($this->page) {
                $result .= $this->page;
            } else {
                if ($limit = $this->getOption('limit'))
                    $result .= " LIMIT $limit";

                if ($offset = $this->getOption('offset'))
                    $result .= " OFFSET $offset";
            }


        return $result;
    }


    function getOption(string $option)
    {
        if (!isset($this->setOptions[$option]))
            return;

        return $this->setOptions[$option];
    }


    function whereMix()
    {
        $result = $this->whereBuilder->getWhere();

        $result = [...$result, ...$this->joinBuilder->getWhere()];

        return $result;
    }



    function getProps(): array | null
    {
        $props = [];

        foreach ($this->propsValues as $coll => $value) {
            if (is_array($value)) {
                foreach ($value as $collIndex => $valueByIndex) {
                    $props["{$coll}_{$collIndex}"] = $valueByIndex;
                }
            } else {
                $props[$coll] = $value;
            }
        }

        $result = [...$props, ...$this->joinBuilder->getProps()];

        return $result;
    }


    function reset()
    {
        $this->request = [];
        $this->propsValues = [];
        $this->selected = [];
        $this->setOptions = [];
        $this->whereBuilder->reset();
        $this->joinBuilder->reset();
        $this->page = null;
        $this->orderByType = 'ASC';
        $this->orderByColls = [];
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
