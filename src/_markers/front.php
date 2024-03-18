<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\front\where\WhereBuilder;
use markorm\front\where\WhereOption;
use markorm\front\SQLBuilder;
use markorm\front\join\JoinBuilder;

/**
 * @property-read WhereBuilder $whereBuilder
 * @property-read SQLBuilder $sqlBuilder
 * @property-read JoinBuilder $joinBuilder

*/
trait front {
    use provider;

   function _whereBuilder(): WhereBuilder { return new WhereBuilder; }
   function whereOption(string $option,  $props, string|false $tableName = false): WhereOption { return new WhereOption($option, $props, $tableName); }
   function _sqlBuilder(): SQLBuilder { return new SQLBuilder; }
   function _joinBuilder(): JoinBuilder { return new JoinBuilder; }

}