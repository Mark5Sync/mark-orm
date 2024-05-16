<?php

namespace markorm\sections;

use markorm\_markers\front;
use markorm\_markers\items;
use markorm\items\WhereItem;

trait where {
    use items;
    use front;
    
    protected function ___where(?string $scheme, array $props)
    {
        $this->sqlBuilder->whereBuilder->push(
            new WhereItem($this->table, 'where', $props, $scheme)
        );
    }

    protected function ___like(?string $scheme, array $props)
    {
        $this->sqlBuilder->whereBuilder->push(
            new WhereItem($this->table, 'like', $props, $scheme)
        );
    }

    protected function ___regexp(?string $scheme, array $props)
    {
        $this->sqlBuilder->whereBuilder->push(
            new WhereItem($this->table, 'regexp', $props, $scheme)
        );
    }

    protected function ___in(?string $scheme, array $props, bool $isNot = false)
    {
        $this->sqlBuilder->whereBuilder->push(
            new WhereItem($this->table, $isNot ? 'notIn' : 'in', $props, $scheme)
        );
    }

    protected function ___fwhere(?string $scheme, array $props)
    {
        $this->sqlBuilder->whereBuilder->push(
            new WhereItem($this->table, 'where', $props, $scheme)
        );
    }


    /**
     * @deprecated
     */
    public function ___isNull(?string $scheme, array $props)
    {
        $this->sqlBuilder->whereBuilder->push(
            new WhereItem($this->table, 'isNull', $props, $scheme, false)
        );
        return $this;
    }



    public function ___isNotNull(?string $scheme, array $props)
    {
        $this->sqlBuilder->whereBuilder->push(
            new WhereItem($this->table, 'isNotNull', $props, $scheme, false)
        );
        return $this;
    }


}