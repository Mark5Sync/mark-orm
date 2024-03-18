<?php

namespace markorm\_system;


use Composer\ClassMapGenerator\ClassMapGenerator;
use marksync\provider\ReflectionMark;
use markorm\_markers\tools;

class ShemeBuilController
{
    use tools;


    private ?\PDO $pdo;
    private ?ReflectionMark $pdoMark;

    function __construct(private string $root, $psr4)
    {
        ini_set('display_errors', 0);

        foreach ($psr4 as $namespace => $folder) {
            $connections = $this->findSourseConnections($folder);
            if (empty($connections))
                die("$folder - ConnectionSource не найдено");


            [$projectFolder, $projectNamespace] = $this->createProjectFolder($folder, $namespace);

            foreach ($connections as $connectionSourceClass) {
                $this->applyConnection($connectionSourceClass);

                $tables = $this->findTables();
                $relationship = $this->getRelationship();

                foreach ($tables as $table => $tableProps) {
                    $this->shemeBuilder($table, $tableProps)
                        ->injectConnection($this->pdoMark)
                        ->setRelationship(isset($relationship[$table]) ? $relationship[$table] : null)
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


    function findSourseConnections($folder): array
    {
        $map = ClassMapGenerator::createMap("$this->root/$folder");
        $result = [];

        foreach ($map as $class => $path) {
            if (str_ends_with($class, 'Model'))
                continue;

            try {
                $reflection = new \ReflectionClass($class);
            } catch (\ReflectionException $th) {
                echo "\n\n{$th->getMessage()}\n\n";
            } catch (\Throwable $th) {
                echo "\n\n -- {$th->getMessage()}\n\n";
            }

            if (!$reflection->isSubclassOf(ConnectionSource::class))
                continue;

            $result[] = $class;
        }

        return $result;
    }


    function applyConnection($connectionSourceClass)
    {
        $this->pdoMark = new ReflectionMark($connectionSourceClass);

        $this->pdo = null;
        $this->pdo = (new ($connectionSourceClass))->pdo;

        echo "\use connection $connectionSourceClass\n";
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

    private function getRelationship()
    {
        $smtp = $this->pdo->prepare("
            SELECT
                table_name,
                column_name,
                referenced_table_name,
                referenced_column_name
            FROM
                information_schema.key_column_usage

            WHERE referenced_table_name is not NULL
        ");

        $smtp->execute();

        $result = [];
        $primarys = [];

        foreach ($smtp->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            [
                'table_name' => $table,
                'column_name' => $coll,
                'referenced_table_name' => $reference,
                'referenced_column_name' => $referenced_column,
            ] = $row;





            $result[$table][$reference] = [
                'coll' => $coll,
                'referenced' => $referenced_column,
            ];

            $result[$reference][$table] = [
                'coll' => $referenced_column,
                'referenced' => $coll,
            ];
        }


        return $result;
    }
}
