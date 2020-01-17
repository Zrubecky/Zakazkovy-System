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

   /** @var string */
   private const ORDERS_TABLE = "orders";

   
   public function __construct(Context $database)
   {
      $this->database = $database;
   }

   
   /**
    * Inserts new order into the database.
    *
    * @param array $orderData [$column => $value].
    * @return ActiveRow
    */
   public function addOrder(array $orderData)
   {
      $order = $this->database->table(self::ORDERS_TABLE)->insert($orderData);

      return $order;
   }

}