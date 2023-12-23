<?php

namespace markorm;

use markorm\_markers\front;

abstract class Model
{
    use front;


    protected string $table;
    protected string $connectionProp;
    private ?string $sql;



    function __construct()
    {
        $this->sqlBuilder->setTable($this->table);
    }

    function getPDO()
    {
        return $this->{$this->connectionProp}->pdo;
    }

    public function select___(array $props)
    {
        $this->sqlBuilder->select($props);
    }

    protected function sel___($props)
    {
        $colls = [];
        foreach ($props as $coll => $pass) {
            if (!$pass)
                continue;

            $colls[] = $coll;
        }
        $this->sqlBuilder->select($colls);
    }

    protected function selectAs___($props)
    {
        $this->sqlBuilder->select($props, true);
    }

    protected function where___($props)
    {
        $this->sqlBuilder->pushWhere('where', $props);
    }

    protected function like___($props)
    {
        $this->sqlBuilder->pushWhere('like', $props);
    }

    protected function regexp___($props)
    {
        $this->sqlBuilder->pushWhere('regexp', $props);
    }

    protected function in___($props)
    {
        $this->sqlBuilder->pushWhere('in', $props);
    }

    protected function fwhere___($props)
    {
        $this->sqlBuilder->pushWhere('where', $props);
    }


    public function wherePrepare($query, $props)
    {
        $this->sqlBuilder->pushWhere('prepare', ['query' => $query, 'props' => $props]);
    }


    public function xwhere(callable $method)
    {
        $this->sqlBuilder->pushWhere('xwhere', $method);
    }


    public function join(Model $model)
    {
        $this->sqlBuilder->push('join', $model);
    }

    public function joinOn(string $fields, Model $model, string $references)
    {
        $this->sqlBuilder->push('joinOn', [
            'model' => $model,
            'fields' => $fields,
            'references' => $references,
        ]);
    }

    public function apply(): bool
    {
        return !!$this->exec();
    }

    public function fetch()
    {
        $stmt = $this->exec();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        $stmt = $this->exec();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    private function exec()
    {
        $props = $this->sqlBuilder->getProps();
        $sql = $this->sqlBuilder->getSQL();

        $this->sql = $sql;

        $stmt = $this->getPDO()->prepare($sql);
        try {
            $stmt->execute($props);
        } catch (\Throwable $th) {
            exit("\nERROR:\n\n >$sql \n\n" . $th->getMessage());
        }

        return $stmt;
    }




    protected function update___($props): bool
    {
        $this->sqlBuilder->push('update', $props);
        return !!$this->exec();
    }

    protected function insert___($props): int
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


    protected function applyOperator___($operator)
    {
        if (!in_array($operator, ['or']))
            throw new \Exception("Undefined OPERATOR [$operator]", 1);

        $this->sqlBuilder->pushWhere($operator, null);
        
    }


    function query(?string &$sql)
    {
        $this->sql = &$sql;
        return $this;
    }
}
