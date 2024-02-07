<?php

namespace markorm\sections;

use markorm\_markers\front;
use markorm\Model;

trait join
{
    use front;
    private $useJoinCascade = false;


    protected function ___useJoinCascade(bool $status = true)
    {
        $this->useJoinCascade = $status;
    }

    
    protected function ___join(Model $model, ?string $references = null, ?string $fields = null, $type = 'left', ?string $joinAs = null)
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
            $joinAs,
        );
    }
}
