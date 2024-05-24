<?php


namespace markorm;

use markorm\_markers\model as markersModel;
use markorm\model\Connection;


abstract class Model extends Connection
{
    use markersModel;

    private ?string $query = '';

    protected function ___sel(array $props)
    {
        $this->getModel()->select(
            ...array_keys(
                $this->request->filter($props, false)
            )
        );
    }


    protected function ___where(string $comparisonOperator, string $logicalOperatorAnd, array $props)
    {
        $props = $this->request->filter($props, false);
        $model = $this->getModel();

        foreach ($props as $coll => $value) {
            $model->where(
                $coll,
                $comparisonOperator,
                $value,
                $logicalOperatorAnd
            );
        }
    }


    // protected function ___in()
    // {
    //     $this->getModel()->whereIn('name', ['masha', 'natasha']);
    // }

    function query(?string &$query)
    {
        $this->query = $query;
        return $this;
    }

    private function bindQuery()
    {
        if (!is_null($this->query))
            return;

        $cloneModel = clone $this->getModel();

        $this->query = $cloneModel->get()->toSql();
    }


    function fetch()
    {
        $this->bindQuery();
        return $this->getModel()->first()->toArray();
    }


    function fetchAll()
    {
        $this->bindQuery();
        return $this->getModel()->get()->toArray();
    }
}
