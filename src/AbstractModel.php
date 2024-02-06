<?php

namespace ___namespace___;
use markorm\Model;
// use {$this->connection->markerClass};
use ___markerClass___;


abstract class ___class___ extends Model {
    // use {$this->connection->marker};
    use __connectionMarker__;

    protected ?array $relationship = __rel__;

    public string $table = __table__;
    protected string $connectionProp = __connectionProp__; 

    function select(...$props){
        $this->___select($props);
        return $this;
    }





    /** 
     * SELECT title FROM ...
    */
    function sel(&$___bool___){
        $this->___sel($props);
        return $this;
    }

    /** 
     * SELECT title as MyTitle FROM ...
    */
    function selectAs(&$___string___){
        $this->___sel($props);
        return $this;
    }


    /** 
     * ... WHERE title LIKE \'%1%\' ...
    */
    function like(&$___string___){
        $this->___sel($props);
        return $this;
    }

    /** 
     * ... WHERE id REGEXP \'1\' ...
    */
    function regexp(&$___string___){
        $this->___sel($props);
        return $this;
    }

    /** 
     * ... WHERE id IN (1, 2, 3)
    */
    function in(&$___array___){
        $this->___in($props);
        return $this;
    }




    /** 
     * IS NULL
    */
    function isNull(&$___bool___){
        $this->___isNull($props);
        return $this;
    }

    /** 
     * IS NOT NULL
    */
    function isNotNull(&$___bool___){
        $this->___isNotNull($props);
        return $this;
    }







    /** 
     * WHERE id = 1
    */
    function where(&$___auto___){
        $this->___where($props);
        return $this;
    }

    /** 
     * ... WHERE id = \'1\'
    */
    function fwhere(&$___string___){
        $this->___where($props);
        return $this;
    }




    /** 
     * ...SET id = 1
    */
    function update(&$___auto___){
        return $this->___update($props);
    }

   /** 
     * ... INSERT (id) VALUES(1)
    */
    function insert(&$___auto___){
        return $this->___insert($props);
    }
    





    function desc(string $description)
    {
        $this->___desc($description);
        return $this;
    }

    function ___get($name)
    {
        $this->___applyOperator($name);
    }

    function join(Model $model)
    {
        $this->___join($model);
        return $this;
    }

    function joinOn(string $fields, Model $model, string $references)
    {
        $this->___join($model, $references, $fields);
        return $this;
    }


    function limit($limit)
    {

        $this->___limit($limit);
        return $this;
    }


    function offset($offset)
    {

        $this->___offset($offset);
        return $this;
    }
}