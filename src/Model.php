<?php


namespace markorm;

use markorm\_markers\model as markersModel;
use markorm\model\Connection;


abstract class Model extends Connection
{
    use markersModel;


    function ___sel(array $props)
    {
        $this->request->set('select', array_keys($this->request->filter($props, false)));
    }

    function fetch()
    {
        return $this->getModel()->first($this->request->get('select', '*'))->toArray();
    }

    function fetchAll()
    {
        return $this->getModel()->get($this->request->get('select', '*'))->toArray();
    }
}
