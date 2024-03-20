<?php

namespace markorm\front\where;

use marksync\provider\Mark;
use markorm\_markers\front;
use markorm\front\where\scheme\SchemeViewer;

#[Mark(mode: Mark::LOCAL)]
class WhereBuilder
{
    use front;

    // /** @var WhereOption[] $options;*/
    // private $options = [];

    /** @var string[] $autoOptions;*/
    private $autoOptions = [];

    /** @var string | false $tableName;*/
    private $tableName = false;





    public SchemeViewer $schemeViewer;
    private ?string $globalScheme = null;



    function setUserSheme(string $userScheme)
    {
        $this->globalScheme = $userScheme;
    }






    function push($option, $props)
    {
        $this->autoOptions[] = $option;


        $this->schemeViewer->setOption($option, $props);

        foreach ($props as $prop => $value) {
            $this->schemeViewer->setOptionProp($option, $prop, [$prop => $props[$prop]]);
        }
        // $this->options[] = new WhereOption($option, $props, $this->tableName);
    }

    function reset()
    {
        $this->schemeViewer->reset();
        // $this->options = [];
    }

    function setTableName($name)
    {
        $this->tableName = $name;
    }

    // function getWhere(): array
    // {
    //     // $result = [];

    //     // foreach ($this->options as $option) {
    //     //     if ($strOptions = $option->toSQL())
    //     //         $result[] = $strOptions;
    //     // }



    //     return $result;
    // }
}
