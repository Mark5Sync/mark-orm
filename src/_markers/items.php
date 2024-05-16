<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\items\WhereItem;

/**

*/
trait items {
    use provider;

   function createWhereItem(string $tablename, $method, array $props, ?string $scheme): WhereItem { return new WhereItem($tablename, $method, $props, $scheme); }

}