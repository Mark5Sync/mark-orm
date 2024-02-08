<?php

namespace markorm\sections;

use markorm\_markers\front;
use markorm\Model;
use markorm\tools\JoinCascadeArray;

trait join
{
    use front;
    private $useJoinCascade = false;
    private $joinArray = [];


    protected function ___useJoinCascade(bool $status = true)
    {
        $this->useJoinCascade = $status;
    }


    private function checkRelationship(Model $model)
    {
        $table = $model->table;

        if (!$this->relationship || !isset($this->relationship[$table]))
            throw new \Exception("No relationships configured for the table $table [JOIN]", 1);

        [
            'coll' => $fields,
            'referenced' => $references,
        ] = $this->relationship[$table];


        return [$fields, $references];
    }


    protected function ___join(Model $model, ?string $references = null, ?string $fields = null, $type = 'left', ?string $joinAs = null)
    {
        if (!$references || !$fields)
            [$fields, $references] = $this->checkRelationship($model);


        $this->sqlBuilder->join(
            $model,
            $references,
            $fields,
            $type,
            $joinAs,
        );

        return $this;
    }


    protected function ___joinCascadeArray(Model $model, ?string $references = null, ?string $fields = null, $type = 'left', ?string $joinAs = null)
    {
        if (!$references || !$fields)
            [$fields, $references] = $this->checkRelationship($model);

        $joinCascadeArray = new JoinCascadeArray(
            $model,
            $references,
            $fields,
            $type,
            $joinAs,
        );
        $joinCascadeArray->parent($this);

        $this->selectAs(...[$fields => "__cascadeJoinArrayBy__$references"]);

        $this->joinArray[$joinAs] = $joinCascadeArray;
    }
}
