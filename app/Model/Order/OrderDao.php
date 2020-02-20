<?php 

declare(strict_types=1);

namespace App\Model\Order;

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

   /**
    * Returns orders for user based on id.
    *
    * @return array
    */
   public function getOrders(int $userId): array
   {
      return $this->database->table(self::ORDERS_TABLE)
      ->where("user_id = ?", $userId)
      ->order("created_at")
      ->fetchAll();
   }

}