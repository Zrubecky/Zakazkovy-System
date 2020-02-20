<?php 

declare(strict_types=1);

namespace App\Model\Order;

use Nette;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

/**
 * Data access object for order types.
 */
class OrderTypeDao
{
   use Nette\SmartObject;

   /** @var Context */
   private $database;

   /** @var string */
   private const TYPES_TABLE = "order_types";

   public function __construct(Context $database)
   {
      $this->database = $database;
   }


   /**
    * Fetches all order types.
    *
    * @return array
    */
   public function findAllTypes(): array
   {
      return $this->database->table(self::TYPES_TABLE)->fetchAll();
   }

   
   /**
    * Fetches order type by type id.
    *
    * @param integer $typeId
    * @return ActiveRow|null
    */
   public function findById(int $typeId): ?ActiveRow
   {
      return $this->database->table(self::TYPES_TABLE)->get($typeId);
   }
}