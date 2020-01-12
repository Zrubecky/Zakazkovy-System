<?php 

namespace App\Model;

use Nette;
use Nette\Database\Context;

/**
 * Data access object for orders.
 */
class OrderDao
{
   use Nette\SmartObject;

   /** @var Context */
   private $database;

   /** @var string */
   private $orderTable;

   
   public function __construct(Context $database)
   {
      $this->database = $database;
      $this->orderTable = "orders";
   }

   
   /**
    * Inserts new order into the database.
    *
    * @param array $orderData [$column => $value].
    * @return ActiveRow
    */
   public function addOrder(array $orderData)
   {
      $order = $this->database->table($this->orderTable)->insert($orderData);

      return $order;
   }

}