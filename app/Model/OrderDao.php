<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\Context;
use Nette\database\Table\ActiveRow;

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
    * @return ActiveRow|null
    */
   public function addOrder(array $orderData): ?ActiveRow
   {
      $order = $this->database->table(self::ORDERS_TABLE)->insert($orderData);

      return ! empty($order) ? $order : null;
   }

}