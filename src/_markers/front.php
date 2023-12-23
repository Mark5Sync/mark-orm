<?php
namespace markorm\_markers;
use markdi\markdi;
use markorm\front\SQLBuilder;
use markorm\front\where\WhereBuilder;
use markorm\front\where\WhereOption;

/**
 * @property-read SQLBuilder $sqlBuilder
 * @property-read WhereBuilder $whereBuilder

*/
trait front {
    use markdi;

   function _sqlBuilder(): SQLBuilder { return new SQLBuilder; }
   function whereBuilder(): WhereBuilder { return new WhereBuilder; }
   function whereOption(string $option,  $props, string|false $tableName = false): WhereOption { return new WhereOption($option, $props, $tableName); }

}