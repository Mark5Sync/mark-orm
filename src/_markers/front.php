<?php
namespace markorm\_markers;
use markdi\markdi;
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
    use markdi;

   function _joinBuilder(): JoinBuilder { return new JoinBuilder; }
   function _sqlBuilder(): SQLBuilder { return new SQLBuilder; }
   function _whereBuilder(): WhereBuilder { return new WhereBuilder; }
   function whereOption(string $option,  $props, string|false $tableName = false): WhereOption { return new WhereOption($option, $props, $tableName); }

}