<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\tools\JoinCascadeArray;
use markorm\tools\ConnectionRecipient;
use markorm\tools\ShemeBuilder;
use markorm\tools\Page;
use markorm\tools\Format;

/**
 * @property-read JoinCascadeArray $joinCascadeArray
 * @property-read ConnectionRecipient $connectionRecipient
 * @property-read Page $page
 * @property-read Format $format

*/
trait tools {
    use provider;

   function joinCascadeArray(): JoinCascadeArray { return new JoinCascadeArray; }
   function connectionRecipient(): ConnectionRecipient { return new ConnectionRecipient; }
   function shemeBuilder(string $table, array $tableProps): ShemeBuilder { return new ShemeBuilder($table, $tableProps); }
   function page(): Page { return new Page; }
   function _format(): Format { return new Format; }

}