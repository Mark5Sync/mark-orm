<?php


namespace testapp;

use testapp\_markers\models;

require '../vendor/autoload.php';


new class
{
    use models;

    function __construct()
    {
        $result = $this->usersModel->sel(name: 1, id: 1)->fetch();
        print_r($result);
    }
};
