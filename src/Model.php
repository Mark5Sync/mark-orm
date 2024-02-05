<?php #ddd

namespace markorm;

use markorm\_markers\front;

abstract class Model
{
    use front;


    public string $table;
    protected string $connectionProp;
    private ?string $sql;
    public $debugQuery = false;

    protected ?array $relationship = null;



    function __construct()
    {
        $this->sqlBuilder->setTable($this->table);
    }

    function getPDO()
    {
        return $this->{$this->connectionProp}->pdo;
    }

    public function ___select(array $props)
    {
        $this->sqlBuilder->select($props);
    }

    protected function ___sel($props)
    {
        $colls = [];
        foreach ($props as $coll => $pass) {
            if (!$pass)
                continue;

            $colls[] = $coll;
        }
        $this->sqlBuilder->select($colls);
    }

    protected function ___selectAs($props)
    {
        $colls = [];
        foreach ($props as $coll => $collAs) {
            if (!$collAs)
                continue;

            $colls[$coll] = $collAs;
        }
        $this->sqlBuilder->select($colls, true);
    }

    protected function ___where($props)
    {
        $this->sqlBuilder->pushWhere('where', $props);
    }

    protected function ___like($props)
    {
        $this->sqlBuilder->pushWhere('like', $props);
    }

    protected function ___regexp($props)
    {
        $this->sqlBuilder->pushWhere('regexp', $props);
    }

    protected function ___in($props)
    {
        $this->sqlBuilder->pushWhere('in', $props);
    }

    protected function ___fwhere($props)
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


    public function ___join(Model $model, ?string $references = null, ?string $fields = null, $type = 'left')
    {
        $table = $model->table;
        if (!$references || !$fields) {
            if (!$this->relationship || !isset($this->relationship[$table]))
                throw new \Exception("No relationships configured for the table $table [JOIN]", 1);

            [
                'coll' => $fields,
                'referenced' => $references,
            ] = $this->relationship[$table];
        }


        $this->sqlBuilder->join(
            $model,
            $references,
            $fields,
            $type,
        );
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

        $this->sql = $this->replace_props($sql, $props);

        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($props);

        return $stmt;
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



    function ___isNull($props){
        $this->sqlBuilder->pushWhere('isNull', $props, false);
        return $this;
    }



    function ___isNotNull($props){
        $this->sqlBuilder->pushWhere('isNotNull', $props, false);
        return $this;
    }



    function transaction()
    {
        return new class($this->getPDO()){
            function __construct(private $pdo){
                $this->pdo->beginTransaction();
            }

            function start(){
                return $this;
            }

            function rollBack(){
                $this->pdo->rollBack();
            }

            function commit(){
                $this->pdo->commit();
            }
        };
    }


    function truncateTable()
    {
        return $this->getPDO()->query("TRUNCATE `$this->table`");
    }
}
