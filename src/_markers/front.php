<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\front\join\JoinBuilder;
use markorm\front\SQLBuilder;
use markorm\front\where\WhereBuilder;
use markorm\front\where\WhereOption;

/**
 * @property-read JoinBuilder $joinBuilder
 * @property-read SQLBuilder $sqlBuilder
 * @property-read WhereBuilder $whereBuilder

*/
trait front {
    use provider;

   function _createJoinBuilder(): JoinBuilder { return new JoinBuilder; }
   function _createSqlBuilder(): SQLBuilder { return new SQLBuilder; }
   function _createWhereBuilder(): WhereBuilder { return new WhereBuilder($this); }
   function createWhereOption(string $option, $props, string|false $tableName = false): WhereOption { return new WhereOption($option, $props, $tableName); }

}