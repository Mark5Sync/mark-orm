<?php

namespace markorm;

use markorm\_markers\front;
use markorm\_markers\log;
use markorm\sections\exec;
use markorm\sections\join;
use markorm\sections\select;
use markorm\sections\where;
use markorm\tools\Page;

abstract class Model
{
    use front;

    use select;
    use where;
    use join;
    use exec;

    use log;



    private string $mark;
    public string $table;
    protected string $connectionProp;
    private ?string $sql;
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


    protected function ___insert($props): int
    {
        $this->sqlBuilder->push('insert', $props);
        $this->exec();

        return $this->getPDO()->lastInsertId();
    }


    function delete(): bool
    {
        $this->sqlBuilder->push('delete', true);
        return !!$this->exec();
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
        $sql = &$this->queryLogs->queryes;
        return $this;
    }



    function ___isNull($props)
    {
        $this->sqlBuilder->pushWhere('isNull', $props, false);
        return $this;
    }



    function ___isNotNull($props)
    {
        $this->sqlBuilder->pushWhere('isNotNull', $props, false);
        return $this;
    }



    function transaction()
    {
        return new class($this->getPDO())
        {
            function __construct(private $pdo)
            {
                $this->pdo->beginTransaction();
            }

            function start()
            {
                return $this;
            }

            function rollBack()
            {
                $this->pdo->rollBack();
            }

            function commit()
            {
                $this->pdo->commit();
            }
        };
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

    function ___mark(string $mark){
        $this->mark = $mark;
        return $this;
    }
}
