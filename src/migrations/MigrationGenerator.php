<?php


namespace markorm\migrations;


class MigrationGenerator
{
    private string $savePath;

    public function __construct(private array $tables, string $savePath)
    {
        $this->setSavePath($savePath);
        $this->generateMigrationClasses();
    }


    private function setSavePath(string $savePath)
    {
        $slag = date('Y-m-d H:i:s');

        $saveFolder = "$savePath/$slag";
        if (!file_exists($saveFolder))
            mkdir($saveFolder, 0777, true);

        $this->savePath = $saveFolder;
    }


    private function generateMigrationClasses()
    {
        foreach ($this->tables as $table) {
            $this->generateMigrationClass($table);
        }
    }

    private function generateMigrationClass($table)
    {
        $className = 'Create' . ucfirst($table['name']) . 'Table';
        $filename = $className . '.php';


        $migrationCode = $this->generateClassCode($className, $table);

        file_put_contents("$this->savePath/$filename", $migrationCode);
    }





    private function generateClassCode($className, $table)
    {
        $tableCode = $this->generateTableCode($table);

        return <<<PHP
            <?php

            use Phinx\Migration\AbstractMigration;

            class $className extends AbstractMigration
            {
                public function change()
                {
            $tableCode
                }
            }
            PHP;
    }

    private function generateTableCode($table)
    {
        $tableName = $table['name'];
        $tableCode = "        \$table = \$this->table('$tableName');\n";

        foreach ($table['colls'] as $column) {
            $tableCode .= $this->generateColumnCode($column);
        }

        $tableCode .= "        \$table->create();\n";

        return $tableCode;
    }

    private function generateColumnCode($column)
    {
        $field = $column['field'];
        $type = strtolower($column['type']);
        $allowNull = $column['allowNull'] ? 'true' : 'false';
        $default = $column['default'] === null ? 'null' : "'" . $column['default'] . "'";
        $autoIncrement = $column['autoIncrement'] ? 'true' : 'false';

        $options = [];
        if ($column['allowNull']) {
            $options[] = "'null' => true";
        }
        if ($column['default'] !== null) {
            $options[] = "'default' => $default";
        }
        if ($column['autoIncrement']) {
            $options[] = "'autoIncrement' => true";
        }

        $optionsString = !empty($options) ? ', [' . implode(', ', $options) . ']' : '';

        return "        \$table->addColumn('$field', '$type'$optionsString)->update();\n";
    }
}