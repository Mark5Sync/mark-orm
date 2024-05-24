<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('id', 'int', ['autoIncrement' => true])->update();
        $table->addColumn('name', 'string')->update();
        $table->addColumn('email', 'string')->update();
        $table->addColumn('phone', 'string', ['null' => true])->update();
        $table->addColumn('password_hash', 'string')->update();
        $table->addColumn('avatar', 'string', ['null' => true])->update();
        $table->create();

    }
}