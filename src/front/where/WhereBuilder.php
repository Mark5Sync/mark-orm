<?php

namespace markorm\front\where;

use marksync\provider\Mark;
use markorm\_markers\front;
use markorm\front\SQLBuilder;
use markorm\items\WhereItem;

#[Mark(mode: Mark::LOCAL, args: ['parent'])]
class WhereBuilder
{
    use front;

    function __construct(private SQLBuilder $sqlBuilder)
    {
        
    }


    private $options = [];
    private $tableName = false;
    private ?string $scheme = null;

    function push(WhereItem $item, string $operator = 'AND')
    {
        if (!$item->isValid)
            return;

        $this->options[] = $operator;
        $this->options[] = $item;

        if ($item->useProps)
            $this->sqlBuilder->pushToPropsValues($item->props);
    }

    function reset()
    {
        $this->options = [];
    }
    
    function getWhere(): array
    {
        if (empty($this->options))
            return [];

        return array_slice($this->options, 1);
    }


    // function setScheme(string $scheme)
    // {
    //     $this->scheme = $scheme;
    // }

}
