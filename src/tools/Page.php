<?php

namespace markorm\tools;

use markdi\NotMark;
use markorm\front\SQLBuilder;

#[NotMark]
class Page
{

    private SQLBuilder $builder;

    function __construct(public int $index, public int $size, public int | false | null &$pages = false)
    {
    }


    function limitFor(SQLBuilder $builder)
    {
        $this->builder = $builder;
        $this->builder->page = $this;
    }


    private function fetchCount()
    {
        $props = $this->builder->getProps();
        $sql = $this->builder->getSelectCount();

        $stmt = $this->builder->getPDO()->prepare($sql);
        $stmt->execute($props);

        $this->pages = ceil($stmt->fetch(\PDO::FETCH_ASSOC)['count'] / $this->size);
    }


    function __toString()
    {
        if (is_null($this->pages))
            $this->fetchCount();

        $limit = $this->size;
        $offset = ($this->index - 1) * $this->size;
        return " LIMIT $limit OFFSET $offset ";
    }
}
