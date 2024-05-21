<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\model\Request;
use markorm\model\Connection;

/**
 * @property-read Request $request
 * @property-read Connection $connection

*/
trait model {
    use provider;

   function _createRequest(): Request { return new Request; }
   function createConnection(): Connection { return new Connection; }

}