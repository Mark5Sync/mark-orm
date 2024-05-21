<?php


namespace testapp\tools;

use markorm\_system\ConnectionSource;

class MyConnection extends ConnectionSource {

    function getConnection(): array
    {
        return [
            'driver'    => 'postgres',
            'host'      => 'postgres',
            'database'  => 'mydatabase',
            'username'  => 'myuser',
            'password'  => 'mypassword',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ];
    }

}
