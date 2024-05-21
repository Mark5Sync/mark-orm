<?php

use markorm\_system\ShemeBuilController;
use marksync\provider\Runner;

require '../vendor/autoload.php';


$composerJson = json_decode(file_get_contents("../composer.json"), true);
new ShemeBuilController('..', ["testapp" => "app/"]);
new Runner('..', ["testapp" => "app/"], 'markers');