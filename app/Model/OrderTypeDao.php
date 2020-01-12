<?php 

namespace App\Model;

use Nette;
use Nette\Database\Context;

/**
 * Data access object for order types.
 */
class OrderTypeDao
{
   use Nette\SmartObject;

   /** @var Context */
   private $database;

   /** @var string */
   private $typeTable;

   public function __construct(Context $database)
   {
      $this->database = $database;
      $this->typeTable = "order_types";
   }


   /**
    * Fetches all order types.
    *
    * @return array
    */
   public function findAllTypes(): array
   {
      return $this->database->table($this->typeTable)->fetchAll();
   }

   
   /**
    * Fetches order type by type id.
    *
    * @param integer $typeId
    * @return ActiveRow|bool
    */
   public function findById(int $typeId)
   {
      return $this->database->table($this->typeTable)->get($typeId);
   }
}