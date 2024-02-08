<?php

namespace markorm\sections;

trait where {

    
    protected function ___where($props)
    {
        $this->sqlBuilder->pushWhere('where', $props);
    }

    protected function ___like($props)
    {
        $this->sqlBuilder->pushWhere('like', $props);
    }

    protected function ___regexp($props)
    {
        $this->sqlBuilder->pushWhere('regexp', $props);
    }

    protected function ___in($props)
    {
        $this->sqlBuilder->pushWhere('in', $props);
        return $this;
    }

    protected function ___fwhere($props)
    {
        $this->sqlBuilder->pushWhere('where', $props);
    }


    public function wherePrepare($query, $props)
    {
        $this->sqlBuilder->pushWhere('prepare', ['query' => $query, 'props' => $props]);
    }


    public function xwhere(callable $method)
    {
        $this->sqlBuilder->pushWhere('xwhere', $method);
    }


}