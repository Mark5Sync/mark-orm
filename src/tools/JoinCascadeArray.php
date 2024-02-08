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


    function parent(Model $parent)
    {
        // $this->parent = $parent;
    }


    function merge(&$result, Model $model)
    {   
        $key = "__cascadeJoinArrayBy__{$this->references}";
        $referencesData = array_column($result, $key);
        unset($result[$key]);

        $result2 = $model->___mergeJoinIn($this->fields, $referencesData, $this->model);
        
    }
}
