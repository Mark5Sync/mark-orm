<?php


namespace markorm\tools;

use markdi\MarkInstance;
use markdi\ReflectionMark;

#[MarkInstance]
class ShemeBuilder
{
    private ?ReflectionMark $connection;

    function __construct(private string $table, private array $tableProps)
    {
    }


    function injectConnection(ReflectionMark $connection){
        $this->connection = $connection;
        return $this;
    }


    function createAbstractModel($folder, $namespace)
    {
        $class = $this->getClassName();
        file_put_contents("$folder/$class.php", $this->getCode($class, $namespace));
    }


    function getCode($class, $namespace)
    {
        return <<<PHP
        <?php

        namespace $namespace;
        use markorm\Model;
        use {$this->connection->markerClass};

        
        abstract class $class extends Model {
            use {$this->connection->marker};

            protected \$relation = [];



            public string \$table = '{$this->table}';
            protected string \$connectionProp = '{$this->connection->prop}'; 

            function select(...\$props){
                \$this->select___(\$props);
                return \$this;
            }

        {$this->createTunelMethod('sel', 'bool', ' SELECT title FROM ... ')}
        
        {$this->createTunelMethod('selectAs', 'string', ' SELECT title as MyTitle FROM ... ')}
        {$this->createTunelMethod('like', 'string', ' ... WHERE title LIKE \'%1%\' ... ')}
        {$this->createTunelMethod('regexp', 'string', ' ... WHERE id REGEXP \'1\' ... ')}
        {$this->createTunelMethod('in', 'array', ' ... WHERE id IN (1, 2, 3) ')}

    


        {$this->createTunelMethod('where', 'auto', ' ... WHERE id = 1 ')}
        {$this->createTunelMethod('fwhere', 'string', ' ... WHERE id = \'1\' ')}


        
        {$this->createTunelMethod('update', 'auto', ' ... SET id = 1 ', returnThis: false)}
        {$this->createTunelMethod('insert', 'auto', ' ... INSERT (id) VALUES(1) ', returnThis: false)}

            function desc(string \$title){
                \$this->desc___(\$title);
                return \$this;
            }

            function ___get(\$name)
            {

                \$this->applyOperator___(\$name);
            }

        }
        PHP;
    }


    private function getClassName()
    {
        $words = explode('_', $this->table);
        $class = '';
        foreach ($words as $word) {
            $class .= ucfirst($word);
        }

        return "Abstract{$class}Model";
    }




    private function createTunelMethod($methodName, $propType, $doc, $default = ' = null', $addNullType = true, $returnThis = true)
    {
        $colls = array_column($this->tableProps, 'coll');

        $nullType = $addNullType ? '?' : '';
        $props = $this->getMethodProps($propType, $colls, $default, $nullType);


        $split = "\n\t\t\t";
        $select = $split . implode(",$split", array_map(fn ($coll) => "'$coll' => \$$coll", $colls));


        $body = "\$this->{$methodName}___([$select
        ]);";
        $body = $returnThis ? "$body$split return \$this;": "return $body";

        return <<<PHP
        
            /**
             * $methodName
             * $doc
            */
            function $methodName($props
            ){
                {$body}
            }

        PHP;
    }


    private function getMethodProps($propType, $colls, $default, $nullType){

        if ($propType == 'auto'){
            $props = [];
            foreach ($this->tableProps as $coll) {
                $props[] = $this->convertToPHPType($coll['type']) . " \${$coll['coll']}{$default}";
            }
    
            $split = "\n\t\t\t?";
            $props =  $split . implode(",$split", $props);
            return $props;
        }


        $split = "\n\t\t\t{$nullType}$propType \$";
        $props =  $split . implode("$default,$split", $colls) . $default;
        return $props;
    }




    private function convertToPHPType($sqlType)
    {
        switch ($sqlType) {
            case 'int': return 'int';
            case 'date': return 'string';

            case 'float':
            case 'decimal': return 'float';

            case 'text':
            case 'longtext':
            case 'varchar': return 'string';

            default:
                throw new \Exception("UNDEFINED Type [$sqlType]", 1);
        }
    }
}
