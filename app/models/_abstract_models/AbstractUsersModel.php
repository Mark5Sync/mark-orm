<?php

namespace testapp\models\_abstract_models;

use markorm\Model;
use testapp\_markers\tools;



abstract class AbstractUsersModel extends Model
{
    use tools;

    protected ?array $relationship = null;

    protected string $table = 'users';
    protected string $connectionProp = 'myConnection';


    protected function getEloquentModel(): AbstractUsersModelEloquent
    {
        return new AbstractUsersModelEloquent;
    }

    // function select(...$props)
    // {
    //     $this->___select($props);
    //     return $this;
    // }

    
    function selectRow(
			&$id = false,
			&$name = false,
			&$email = false)
    {
        $_cijcbb32ojsallk4ms = $this->sel(...$this->request->filter([
			'id' => $id,
			'name' => $name,
			'email' => $email], false, 1))->fetch();

        if ($_cijcbb32ojsallk4ms)
            foreach ($_cijcbb32ojsallk4ms as $_jjfj23i2nnm2nm3nm4 => $_jjjfjij2i2i3j4nnvkxjlkjd) {
                $$_jjfj23i2nnm2nm3nm4 = $_jjjfjij2i2i3j4nnvkxjlkjd;
            }
    }


    /** 
     * SELECT title FROM ...
     */
    function sel(
			bool $id = false,
			bool $name = false,
			bool $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___sel($props);
        return $this;
    }

    /** 
     * SELECT title as MyTitle FROM ...
     */
    function selectAs(
			false | string $id = false,
			false | string $name = false,
			false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
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
    function like(?string $_ = null, 
			false | string $id = false,
			false | string $name = false,
			false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___like($_, $props);
        return $this;
    }

    /** 
     * ... WHERE id REGEXP \'1\' ...
     */
    function regexp(?string $_ = null, 
			false | string $id = false,
			false | string $name = false,
			false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___regexp($_, $props);
        return $this;
    }

    /** 
     * ... WHERE id IN (1, 2, 3)
     */
    function in(?string $_ = null, 
			false | array $id = false,
			false | array $name = false,
			false | array $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___in($_, $props);
        return $this;
    }


    /** 
     * ... WHERE id IN (1, 2, 3)
     */
    function notIn(?string $_ = null, 
			false | array $id = false,
			false | array $name = false,
			false | array $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___in($_, $props, true);
        return $this;
    }



    /** 
     * IS NULL
     */
    function isNull(?string $_ = null, 
			bool $id = false,
			bool $name = false,
			bool $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___isNull($_, $props);
        return $this;
    }

    /** 
     * IS NOT NULL
     */
    function isNotNull(?string $_ = null, 
			bool $id = false,
			bool $name = false,
			bool $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___isNotNull($_, $props);
        return $this;
    }







    /** 
     * WHERE id = 1
     */
    function where(?string $_ = null, 
			 false | int $id = false,
			 false | string $name = false,
			 false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___where($_, $props);
        return $this;
    }

    /** 
     * ... WHERE id = \'1\'
     */
    function fwhere(?string $_ = null, 
			false | string $id = false,
			false | string $name = false,
			false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___where($_, $props);
        return $this;
    }




    /** 
     * ...SET id = 1
     */
    function updt(
			 false | int $id = false,
			 false | string $name = false,
			 false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        return $this->___update($props);
    }

    /** 
     * ... INSERT (id) VALUES(1)
     */
    function insert(
			 false | int $id = false,
			 false | string $name = false,
			 false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        return $this->___insert($props);
    }


    /** 
     * ... INSERT (id) VALUES(1) ON DUBLICATE UPDATE
     */
    function insertOnDublicateUpdate(
			 false | int $id = false,
			 false | string $name = false,
			 false | string $email = false)
    {
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
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


    function orderByAsc(
			bool $id = false,
			bool $name = false,
			bool $email = false){
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___orderBy('ASC', $props);
        return $this;
    }


    function orderByDesc(
			bool $id = false,
			bool $name = false,
			bool $email = false){
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
        $this->___orderBy('DESC', $props);
        return $this;
    }


    function groupBy(
			bool $id = false,
			bool $name = false,
			bool $email = false){
        $props = [
			'id' => $id,
			'name' => $name,
			'email' => $email];
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


