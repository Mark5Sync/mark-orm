<?php


namespace testapp;

use markorm\_system\ConnectionSource;

class MyConnection extends ConnectionSource {

    function getConnection(): array
    {
        return [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'your_database',
            'username'  => 'your_username',
            'password'  => 'your_password',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ];
    }

}
