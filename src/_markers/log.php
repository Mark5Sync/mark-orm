<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\log\QueryLogs;

/**
 * @property-read QueryLogs $queryLogs

*/
trait log {
    use provider;

   function createQueryLogs(): QueryLogs { return new QueryLogs; }

}