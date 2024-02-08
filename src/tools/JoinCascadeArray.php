<?php

namespace markorm\tools;

use markdi\NotMark;
use markorm\Model;

#[NotMark]
class JoinCascadeArray
{

    function __construct(
        private Model $model,
        private string $references,
        private string $fields,
        private string $type,
        private string $joinAs,
    ) {
    }


    function merge(&$result, Model $model)
    {   
        $mergeCollName = "__cascadeJoinArrayBy__{$this->references}";
        $referencesData = array_column($result, $mergeCollName);

        $data = $model->___mergeJoinIn($this->fields, $referencesData, $this->model, $this->references);
        

        foreach ($result as &$row) {
            $mergeValue = $row[$mergeCollName];
            unset($row[$mergeCollName]);

            $row[$this->joinAs] = $data[$mergeValue];
        }
    }
}
