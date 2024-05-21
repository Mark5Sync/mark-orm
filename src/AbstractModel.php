<?php

namespace ___namespace___;

use markorm\Model;
use ___markerClass___;



abstract class ___class___ extends Model
{
    use __connectionMarker__;

    protected ?array $relationship = __rel__;

    protected string $table = '__table__';
    protected string $connectionProp = '__connectionProp__';


    protected function getEloquentModel(): ___class___Eloquent
    {
        return new ___class___Eloquent;
    }

    // function select(...$props)
    // {
    //     $this->___select($props);
    //     return $this;
    // }

    
    function selectRow(&$___bind___)
    {
        $result = $this->sel(...$this->___filterRowProps([$___restruct_bind___]))->limit(1)->fetch();

        if ($result)
            foreach ($result as $prop => $value) {
                $$prop = $value;
            }
    }


    /** 
     * SELECT title FROM ...
     */
    function sel(&$___bool___)
    {
        $props = [$___restruct_bool___];
        $this->___sel($props);
        return $this;
    }

    /** 
     * SELECT title as MyTitle FROM ...
     */
    function selectAs(&$___string___)
    {
        $props = [$___restruct_string___];
        $this->___selectAs($props);
        return $this;
    }


    // function selectDate(?string $_ = null, &$___string_date___)
    // {
    //     $props = [$___restruct_string_date___];
    //     $this->___selectDate($props);
    //     return $this;
    // }


    /** 
     * ... WHERE title LIKE \'%1%\' ...
     */
    function like(?string $_ = null, &$___string___)
    {
        $props = [$___restruct_string___];
        $this->___like($_, $props);
        return $this;
    }

    /** 
     * ... WHERE id REGEXP \'1\' ...
     */
    function regexp(?string $_ = null, &$___string___)
    {
        $props = [$___restruct_string___];
        $this->___regexp($_, $props);
        return $this;
    }

    /** 
     * ... WHERE id IN (1, 2, 3)
     */
    function in(?string $_ = null, &$___array___)
    {
        $props = [$___restruct_array___];
        $this->___in($_, $props);
        return $this;
    }


    /** 
     * ... WHERE id IN (1, 2, 3)
     */
    function notIn(?string $_ = null, &$___array___)
    {
        $props = [$___restruct_array___];
        $this->___in($_, $props, true);
        return $this;
    }



    /** 
     * IS NULL
     */
    function isNull(?string $_ = null, &$___bool___)
    {
        $props = [$___restruct_bool___];
        $this->___isNull($_, $props);
        return $this;
    }

    /** 
     * IS NOT NULL
     */
    function isNotNull(?string $_ = null, &$___bool___)
    {
        $props = [$___restruct_bool___];
        $this->___isNotNull($_, $props);
        return $this;
    }







    /** 
     * WHERE id = 1
     */
    function where(?string $_ = null, &$___auto___)
    {
        $props = [$___restruct_auto___];
        $this->___where($_, $props);
        return $this;
    }

    /** 
     * ... WHERE id = \'1\'
     */
    function fwhere(?string $_ = null, &$___string___)
    {
        $props = [$___restruct_string___];
        $this->___where($_, $props);
        return $this;
    }




    /** 
     * ...SET id = 1
     */
    function updt(&$___auto___)
    {
        $props = [$___restruct_auto___];
        return $this->___update($props);
    }

    /** 
     * ... INSERT (id) VALUES(1)
     */
    function insert(&$___auto___)
    {
        $props = [$___restruct_auto___];
        return $this->___insert($props);
    }


    /** 
     * ... INSERT (id) VALUES(1) ON DUBLICATE UPDATE
     */
    function insertOnDublicateUpdate(&$___auto___)
    {
        $props = [$___restruct_auto___];
        return $this->___insertOnDublicateUpdate($props);
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


    function joinCascade(...$models)
    {

        foreach ($models as $propName => $model) {
            $this->___join($model, null, null, 'left', $propName);
        }

        return $this;
    }


    function joinCascadeArray(...$models)
    {

        foreach ($models as $propName => $model) {
            $this->___joinCascadeArray($model, null, null, 'left', $propName);
        }

        return $this;
    }


    function page(int $index, int $size, int | false | null &$pages = false)
    {
        $this->___page($index, $size, $pages);
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


    function orderByAsc(&$___bool___){
        $props = [$___restruct_bool___];
        $this->___orderBy('ASC', $props);
        return $this;
    }


    function orderByDesc(&$___bool___){
        $props = [$___restruct_bool___];
        $this->___orderBy('DESC', $props);
        return $this;
    }


    function groupBy(&$___bool___){
        $props = [$___restruct_bool___];
        $this->___groupBy($props);
        return $this;
    }


    function mark(string $mark)
    {
        $this->___mark($mark);
        return $this;
    }





    function whereScheme(string $scheme){
        $this->___whereScheme($scheme);
        return $this;
    }
}


