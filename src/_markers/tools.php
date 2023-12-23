<?php
namespace markorm\_markers;
use markdi\markdi;
use markorm\tools\ConnectionRecipient;
use markorm\tools\ShemeBuilder;

/**
 * @property-read ConnectionRecipient $connectionRecipient

*/
trait tools {
    use markdi;

   function connectionRecipient(): ConnectionRecipient { return new ConnectionRecipient; }
   function shemeBuilder(string $table, array $tableProps): ShemeBuilder { return new ShemeBuilder($table, $tableProps); }

}