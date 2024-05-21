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

   function createJoinCascadeArray(): JoinCascadeArray { return new JoinCascadeArray; }
   function createConnectionRecipient(): ConnectionRecipient { return new ConnectionRecipient; }
   function createShemeBuilder(string $table, array $tableProps): ShemeBuilder { return new ShemeBuilder($table, $tableProps); }
   function createPage(): Page { return new Page; }
   function _createFormat(): Format { return new Format; }

}