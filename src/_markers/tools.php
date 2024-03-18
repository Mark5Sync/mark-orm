<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\tools\Page;
use markorm\tools\JoinCascadeArray;
use markorm\tools\Format;
use markorm\tools\ShemeBuilder;
use markorm\tools\ConnectionRecipient;

/**
 * @property-read Page $page
 * @property-read JoinCascadeArray $joinCascadeArray
 * @property-read Format $format
 * @property-read ConnectionRecipient $connectionRecipient

*/
trait tools {
    use provider;

   function page(): Page { return new Page; }
   function joinCascadeArray(): JoinCascadeArray { return new JoinCascadeArray; }
   function _format(): Format { return new Format; }
   function shemeBuilder(string $table, array $tableProps): ShemeBuilder { return new ShemeBuilder($table, $tableProps); }
   function connectionRecipient(): ConnectionRecipient { return new ConnectionRecipient; }

}