<?php


namespace markorm\sections;

use markorm\_markers\front;
use markorm\Model;
use markorm\tools\JoinCascadeArray;


trait join
{
    use front;

    private $joinsModel = [];
    private $joinsCascade = [];


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


    function getJoinModels()
    {
        return $this->joinsModel;
    }


    protected function ___join(Model $model, ?string $references = null, ?string $fields = null, $type = 'left', ?string $joinAs = null)
    {
        if (!$references || !$fields)
            [$fields, $references] = $this->checkRelationship($model);

        $this->mergeModels($model);

        $this->sqlBuilder->join(
            $model,
            $references,
            $fields,
            $type,
            $joinAs,
        );


        if ($joinAs)
            $this->selectAs(...[$fields => "__cascadeJoinField__$joinAs"]);

        return $this;
    }


    protected function ___joinCascadeArray(Model $model, ?string $references = null, ?string $fields = null, $type = 'left', ?string $joinAs = null)
    {
        if (!$references || !$fields)
            [$fields, $references] = $this->checkRelationship($model);

        $this->mergeModels($model);

        $joinCascadeArray = new JoinCascadeArray(
            $model,
            $references,
            $fields,
            $type,
            $joinAs,
        );

        $this->selectAs(...[$fields => "__cascadeJoinArrayBy__$references"]);

        $this->joinsCascade[$joinAs] = $joinCascadeArray;
    }


    private function mergeModels(Model $model)
    {
        $this->joinsModel = [...$this->joinsModel, $model, $model->getJoinModels()];
    }



    private function applyCascadeMerge(array $data)
    {
        foreach ([$this, ...$this->joinsModel] as $model) {
            if (empty($model->joinsCascade))
                continue;

            $joinsModel = $model->joinsCascade;
            $model->joinsCascade = [];

            foreach ($joinsModel as $joinAs => $joinCascadeArray) {
                $joinCascadeArray->merge($data, $model);
            }

            return $data;
        }

        return;
    }
}
