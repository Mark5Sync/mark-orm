<?php

use Phinx\Migration\AbstractMigration;

class CreateBasketsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('baskets');
        $table->addColumn('id', 'int', ['autoIncrement' => true])->update();
        $table->addColumn('title', 'string', ['null' => true])->update();
        $table->addColumn('userId', 'int', ['null' => true])->update();
        $table->addColumn('price', 'float', ['null' => true])->update();
        $table->create();

    }
}