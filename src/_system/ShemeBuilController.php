<?php

namespace markorm\_system;


use Composer\ClassMapGenerator\ClassMapGenerator;
use markdi\ReflectionMark;
use markorm\_markers\tools;

class ShemeBuilController
{
    use tools;


    private ?\PDO $pdo;
    private ?ReflectionMark $pdoMark;

    function __construct(private string $root, $psr4)
    {

        foreach ($psr4 as $namespace => $folder) {
            [$projectFolder, $projectNamespace] = $this->createProjectFolder($folder, $namespace);

            foreach ($this->mapConnections($folder) as $connectionClass) {
                echo "\use connection $connectionClass\n";

                $tables = $this->findTables();

                foreach ($tables as $table => $tableProps) {
                    $this->shemeBuilder($table, $tableProps)
                         ->injectConnection($this->pdoMark)
                         ->createAbstractModel($projectFolder, $projectNamespace);
                }
            }
        }
    }


    function createProjectFolder($projectSrcFolder, $namespace)
    {
        $projectFolder = "{$this->root}/{$projectSrcFolder}_abstract_models";

        if (file_exists($projectFolder))
            $this->rrmdir($projectFolder);

        mkdir($projectFolder, 0777, true);

        return [$projectFolder, "{$namespace}_abstract_models"];
    }


    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                        $this->rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    else
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
            rmdir($dir);
        }
    }


    function mapConnections($folder)
    {
        $map = ClassMapGenerator::createMap("$this->root/$folder");

        foreach ($map as $class => $path) {
            $reflection = new \ReflectionClass($class);

            if (!$reflection->isSubclassOf(ConnectionSource::class))
                continue;

            $this->pdoMark = new ReflectionMark($class);

            $this->pdo = null;
            $this->pdo = (new ($class))->pdo;
            yield $class;
        }
    }



    function findTables()
    {
        $stmt = $this->pdo->prepare("SELECT 
                colls.TABLE_NAME as 'table',
                colls.COLUMN_NAME as 'coll',
                colls.COLUMN_DEFAULT as 'default',
                colls.DATA_TYPE as 'type',
                colls.COLUMN_TYPE as 'collType',
                colls.IS_NULLABLE as 'isNull',
                colls.COLUMN_COMMENT as 'comment',
                colls.EXTRA as 'extra'

            FROM INFORMATION_SCHEMA.TABLES as tables

            LEFT JOIN INFORMATION_SCHEMA.COLUMNS as colls
            ON tables.TABLE_NAME = colls.TABLE_NAME

            WHERE TABLE_TYPE='BASE TABLE'
        ");
        $stmt->execute();

        $result = [];

        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $table = $row['table'];
            unset($row['table']);
            $result[$table][] = $row;
        }

        return $result;
    }
}
