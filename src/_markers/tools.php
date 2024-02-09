<?php
namespace markorm\_markers;
use markdi\markdi;
use markorm\tools\JoinCascadeArray;
use markorm\tools\QueryLogs;
use markorm\tools\ShemeBuilder;
use markorm\tools\ConnectionRecipient;
use markorm\tools\Page;

/**
 * @property-read JoinCascadeArray $joinCascadeArray
 * @property-read QueryLogs $queryLogs
 * @property-read ConnectionRecipient $connectionRecipient
 * @property-read Page $page

*/
trait tools {
    use markdi;

   function joinCascadeArray(): JoinCascadeArray { return new JoinCascadeArray; }
   function queryLogs(): QueryLogs { return new QueryLogs; }
   function shemeBuilder(string $table, array $tableProps): ShemeBuilder { return new ShemeBuilder($table, $tableProps); }
   function connectionRecipient(): ConnectionRecipient { return new ConnectionRecipient; }
   function page(): Page { return new Page; }

}