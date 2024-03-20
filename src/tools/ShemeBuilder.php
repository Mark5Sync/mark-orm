<?php

namespace markorm\tools;

use marksync\provider\MarkInstance;
use marksync\provider\ReflectionMark;

#[MarkInstance]
class ShemeBuilder
{
    private ?ReflectionMark $connection;
    private ?array $relationship;
    private string $class;
    private string $classArtisan;


    function __construct(
        private string $table,
        private array $tableProps,
    ) {

        $className = $this->getClassName();
        $this->class = "Abstract{$className}Model";
        $this->classArtisan = "{$className}MSV";
    }

    private function getClassName()
    {
        $words = explode('_', $this->table);
        $class = '';
        foreach ($words as $word) {
            $class .= ucfirst($word);
        }

        return "{$class}";
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
        $this->checkFolder("$folder/schemes");

        $artisanNameSpace = "$namespace\\schemes";

        file_put_contents("$folder/schemes/{$this->classArtisan}.php", $this->getSchemeCode($artisanNameSpace));
        file_put_contents("$folder/{$this->class}.php", $this->getCode($namespace, $artisanNameSpace));
    }

    function checkFolder($folder)
    {
        if (!file_exists($folder))
            mkdir($folder, 0777, true);
    }

    private function getSchemeCode($namespace)
    {
        $colls = array_column($this->tableProps, 'coll');
        $viewerProps = [];

        foreach ([null, ...$colls] as $coll) {
            if ($coll)
                $viewerProps[] = "\t\t/** -- $coll -- */";


            foreach (['where', 'in', 'notIn', 'like', 'regexp', 'isNull', 'isNotNull'] as $option) {

                $viewerProps[] = "\t\tpublic \$" . ($coll ? $option . ucfirst($coll) : $option) . ";";
            }

            $viewerProps[] = "\n\n";
        }

        $props = [
            '__table__' => $this->table,
            '___namespace___' => $namespace,
            '___classArtisan___' => $this->classArtisan,
            '___ArtisanContent___' => implode("\n", $viewerProps),
        ];

        $abstactCode = file_get_contents(__DIR__ . "/../ArtisanTable.php");
        foreach ($props as $key => $value) {
            $abstactCode = str_replace($key, $value, $abstactCode);
        }

        return $abstactCode;
    }


    function getCode($namespace, $artisanNameSpace)
    {
        $split = "\n\t\t\t";
        $rel = $this->getRelationship();
        $colls = array_column($this->tableProps, 'coll');


        $props = [
            '___namespace___' => $namespace,
            '___markerClass___' => $this->connection->markerClass,
            '___class___' => $this->class,
            '__connectionMarker__' => $this->connection->marker,
            '__rel__' => $rel,
            '__table__' => $this->table,
            '__connectionProp__' => $this->connection->prop,
            '___ArtisanNameSpace___' => "$artisanNameSpace\\{$this->classArtisan}",
            '___ArtisanClass___' => $this->classArtisan,
        ];


        $abstactCode = file_get_contents(__DIR__ . "/../AbstractModel.php");
        foreach ($props as $key => $value) {
            $abstactCode = str_replace($key, $value, $abstactCode);
        }


        foreach (['auto', 'bool', 'array', 'string'] as $propsType) {
            $input = $this->getMethodProps($propsType, $colls, ' = false', $propsType == 'bool' ? '' : 'false | ');
            $restruct = $split . implode(",$split", array_map(fn ($coll) => "'$coll' => \$$coll", $colls));

            $abstactCode = str_replace("&\$___{$propsType}___", $input, $abstactCode);
            $abstactCode = str_replace("\$___restruct_{$propsType}___", $restruct, $abstactCode);
        }

        return $abstactCode;
    }




    private function getMethodProps($propType, $colls, $default, $nullType)
    {

        if ($propType == 'auto') {
            $props = [];
            foreach ($this->tableProps as $coll) {
                $phpType = $this->convertToPHPType($coll['type']) . " \${$coll['coll']}{$default}";
                $props[] = ($coll['isNull'] == 'YES' ? 'null | ' : '') . $phpType;
            }

            $split = "\n\t\t\t false | ";
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
            case 'bigint':
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
