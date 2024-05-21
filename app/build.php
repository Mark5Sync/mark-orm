<?php

use markorm\_system\ShemeBuilController;


require '../vendor/autoload.php';


$composerJson = json_decode(file_get_contents("../composer.json"), true);
new ShemeBuilController('..', ["testapp" => "app/"]);