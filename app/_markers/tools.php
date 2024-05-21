<?php
namespace testapp\_markers;
use marksync\provider\provider;
use testapp\tools\MyConnection;
use testapp\tools\env;

/**
 * @property-read MyConnection $myConnection
 * @property-read env $env

*/
trait tools {
    use provider;

   function createMyConnection(): MyConnection { return new MyConnection; }
   function createEnv(): env { return new env; }

}