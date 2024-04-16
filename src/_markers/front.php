<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\front\join\JoinBuilder;
use markorm\front\SQLBuilder;
use markorm\front\where\WhereOption;
use markorm\front\where\WhereBuilder;

/**
 * @property-read JoinBuilder $joinBuilder
 * @property-read SQLBuilder $sqlBuilder
 * @property-read WhereBuilder $whereBuilder

*/
trait front {
    use provider;

   function _joinBuilder(): JoinBuilder { return new JoinBuilder; }
   function _sqlBuilder(): SQLBuilder { return new SQLBuilder; }
   function whereOption(string $option, $props, string|false $tableName = false): WhereOption { return new WhereOption($option, $props, $tableName); }
   function _whereBuilder(): WhereBuilder { return new WhereBuilder; }

}