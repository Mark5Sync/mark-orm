<?php
namespace markorm\_markers;
use markdi\markdi;
use markorm\log\QueryLogs;

/**
 * @property-read QueryLogs $queryLogs

*/
trait log {
    use markdi;

   function queryLogs(): QueryLogs { return new QueryLogs; }

}