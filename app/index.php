<?php


namespace testapp;

use testapp\_markers\models;

require '../vendor/autoload.php';


new class
{
    use models;

    function __construct()
    {
        $this->usersModel->where(id: 4, name: 22)->selectRow(name: $name);  //sel(name: 1)->fetch();
        echo "$name\n";
    }
};
