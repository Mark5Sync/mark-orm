<?php


namespace markorm\tools;

use markdi\MarkInstance;
use markdi\ReflectionMark;

#[MarkInstance]
class ShemeBuilder
{
    private ?ReflectionMark $connection;
    private ?array $relationship;


    function __construct(
        private string $table,
        private array $tableProps,

    ) {
    }


    function injectConnection(ReflectionMark $connection)
    {
        $this->connection = $connection;
        return $this;
    }

    function setRelationship(?array $relationship)
    {
        $this->relationship = $relationship;
        return $this;
    }

    function getRelationship(): string
    {
        if (!$this->relationship)
            return 'null';

        return var_export($this->relationship, true);
    }

    function createAbstractModel($folder, $namespace)
    {
        $class = $this->getClassName();
        file_put_contents("$folder/$class.php", $this->getCode($class, $namespace));
    }


    function getCode($class, $namespace)
    {
        $split = "\n\t\t\t";
        $rel = $this->getRelationship();

        $props = [
            '___namespace___' => $namespace,
            '___markerClass___' => $this->connection->markerClass,
            '___class___' => $class,
            '__connectionMarker__' => $this->connection->marker,
            '__rel__' => $rel,
            '__table__' => $this->table,
            '__connectionProp__' => $this->connection->prop,
        ];

        $abstactCode = file_get_contents(__DIR__ . "/../AbstractModel.php");
        foreach ($props as $key => $value) {
            $abstactCode = str_replace($key, $value, $abstactCode);
        }

        $colls = array_column($this->tableProps, 'coll');
        foreach (['auto', 'bool', 'array', 'string'] as $propsType) {
            $input = $this->getMethodProps($propsType, $colls, ' = null', true);
            $restruct = $split . implode(",$split", array_map(fn ($coll) => "'$coll' => \$$coll", $colls));

            $abstactCode = str_replace("&\$___{$propsType}___", $input, $abstactCode);
            $abstactCode = str_replace("\$___restruct_{$propsType}___", $restruct, $abstactCode);
        }

        return $abstactCode;
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


        $body = "\$this->___{$methodName}([$select
        ]);";
        $body = $returnThis ? "$body$split return \$this;" : "return $body";

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


    private function getMethodProps($propType, $colls, $default, $nullType)
    {

        if ($propType == 'auto') {
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
            case 'int':
                return 'int';
            case 'date':
            case 'datetime':
                return 'string';

            case 'float':
            case 'decimal':
                return 'float';

            case 'text':
            case 'longtext':
            case 'varchar':
                return 'string';

            default:
                throw new \Exception("UNDEFINED Type [$sqlType]", 1);
        }
    }
}
