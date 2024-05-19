<?php

namespace markorm;

use markorm\_markers\front;
use markorm\_markers\log;
use markorm\sections\exec;
use markorm\sections\join;
use markorm\sections\select;
use markorm\sections\where;
use markorm\tools\Page;
use markorm\transact\Transaction;

abstract class Model
{



    private string $mark;
    public string $table;
    protected string $connectionProp;
    private ?string $sql;

    private ?array $insertData;

    public $debugQuery = false;

    protected ?array $relationship = null;
    private $page = null;



    function __construct()
    {
        $this->sqlBuilder->setTable($this->table);
        $this->sqlBuilder->parentModel = $this;
    }


    function getPDO()
    {
        return $this->{$this->connectionProp}->pdo;
    }


    protected function ___desc()
    {
    }


    private function replace_props($query, $props)
    {
        foreach ($props as $prop => $value) {
            $query = str_replace(":$prop", var_export($value, true), $query);
        }

        return $query;
    }


    protected function ___update($props): bool
    {
        $this->sqlBuilder->push('update', $props);
        return !!$this->exec();
    }


    /**
     * bind
     */
    public function insertData(null &$data)
    {
        $this->insertData = &$data;
        return $this;
    }

    protected function ___insert(array $props): int
    {
        $this->insertData = $this->clearInsert($props);
        $this->sqlBuilder->push('insert', $this->insertData);
        
        $this->exec();

        return $this->getPDO()->lastInsertId();
    }


    private function clearInsert(array $props)
    {
        $result = [];
        foreach ($props as $key => $value) {
            if ($value === false)
                continue;

            $result[$key] = $value;
        }

        return $result;
    }


    protected function ___insertOnDublicateUpdate($props): int
    {
        $this->sqlBuilder->push('insertOnDublicateUpdate', $props);
        $this->exec();

        return $this->getPDO()->lastInsertId();
    }


    function delete(): int
    {
        $this->sqlBuilder->push('delete', true);
        $smtp = $this->exec();

        $count = $smtp->rowCount();
        return $count;
    }


    protected function ___applyOperator($operator)
    {
        if (!in_array($operator, ['or']))
            throw new \Exception("Undefined OPERATOR [$operator]", 1);

        $this->sqlBuilder->pushWhere($operator, null);
    }


    protected function ___page(int $index, int $size, int | false | null &$pages = false)
    {
        $page = new Page($index, $size, $pages);
        $page->limitFor($this->sqlBuilder);
    }


    protected function ___limit($value)
    {
        $this->sqlBuilder->set('limit', $value);
    }


    protected function ___offset($value)
    {
        $this->sqlBuilder->set('offset', $value);
    }


    function query(?string &$sql)
    {
        $this->sql = &$sql;
        return $this;
    }




    function ___orderBy($type, $props)
    {
        $colls = [];
        foreach ($props as $coll => $pass) {
            if (!$pass)
                continue;

            $colls[] = $coll;
        }
        $this->sqlBuilder->setOrderBy($type, $colls);
        return $this;
    }


    function ___groupBy($props)
    {
        $colls = [];
        foreach ($props as $coll => $pass) {
            if (!$pass)
                continue;

            $colls[] = $coll;
        }
        $this->sqlBuilder->setGroupBy($colls);
        return $this;
    }



    function transaction()
    {
        return new Transaction($this->getPDO());
    }



    function truncateTable()
    {
        return $this->getPDO()->query("TRUNCATE `$this->table`");
    }


    function ___mergeJoinIn($mainColl, $mainData, Model $model, string $modelColl)
    {
        $this->join(
            $model->selectAs(...[$modelColl => "__cascadeJoinArrayBy__$mainColl"])
        )
            ->in(...[$mainColl => $mainData]);


        $result = $this->joinByCollumn($this->fetchAll(), "__cascadeJoinArrayBy__$mainColl");

        return $result;
    }

    private function joinByCollumn(array $rows, string $mergeCollName)
    {
        $result = [];

        foreach ($rows as $row) {
            $key = $row[$mergeCollName];
            unset($row[$mergeCollName]);
            $result[$key][] = $row;
        }

        return $result;
    }

    function ___mark(string $mark)
    {
        $this->mark = $mark;
        return $this;
    }



    function ___whereScheme(string $scheme)
    {
        $this->sqlBuilder->whereBuilder->setScheme($scheme);
    }


    protected function ___filterRowProps(array $props){
        $result = [];

        foreach ($props as $prop => $value) {
            if (is_null($value))
                $result[$prop] = 1;
        }

        return $result;
    }
}
