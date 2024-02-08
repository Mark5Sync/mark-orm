<?php
namespace markorm\_markers;
use markdi\markdi;
use markorm\front\SQLBuilder;
use markorm\front\where\WhereOption;
use markorm\front\where\WhereBuilder;
use markorm\front\join\JoinBuilder;

/**
 * @property-read SQLBuilder $sqlBuilder
 * @property-read WhereBuilder $whereBuilder
 * @property-read JoinBuilder $joinBuilder

*/
trait front {
    use markdi;

   function _sqlBuilder(): SQLBuilder { return new SQLBuilder; }
   function whereOption(string $option,  $props, string|false $tableName = false): WhereOption { return new WhereOption($option, $props, $tableName); }
   function _whereBuilder(): WhereBuilder { return new WhereBuilder; }
   function _joinBuilder(): JoinBuilder { return new JoinBuilder; }

}