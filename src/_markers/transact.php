<?php
namespace markorm\_markers;
use marksync\provider\provider;
use markorm\transact\Transaction;
use markorm\transact\TransactionController;

/**
 * @property-read Transaction $transaction
 * @property-read TransactionController $transactionController

*/
trait transact {
    use provider;

   function transaction(): Transaction { return new Transaction; }
   function transactionController(): TransactionController { return new TransactionController; }

}