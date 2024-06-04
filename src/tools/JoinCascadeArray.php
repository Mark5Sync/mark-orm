<?php

namespace markorm\tools;

use marksync\provider\NotMark;
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

        if (!empty($referencesData))
            $data = $model->___mergeJoinIn($this->fields, $referencesData, $this->model, $this->references);
        else
            $data[$mergeCollName] = [];
        // throw new \Exception("JoinCascadeArray - столбец $mergeCollName пуст", 1);



        foreach ($result as &$row) {
            $mergeValue = $row[$mergeCollName];
            unset($row[$mergeCollName]);

            $row[$this->joinAs] = $data[$mergeValue];
        }
    }
}
