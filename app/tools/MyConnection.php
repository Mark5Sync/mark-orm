<?php


namespace testapp\tools;

use markorm\_system\ConnectionSource;

class MyConnection extends ConnectionSource {

    function getConnection(): array
    {
        return [
            'driver'    => 'mysql',
            'host'      => 'mariadb',
            'database'  => 'mydatabase',
            'username'  => 'user',
            'password'  => '111',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ];
    }

}
