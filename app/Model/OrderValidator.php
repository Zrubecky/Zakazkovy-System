<?php 

namespace App\Model;

use Nette;
use Nette\Utils\DateTime;

/**
 * Handles order date validation.
 */
class OrderValidator 
{
   use Nette\SmartObject;

   
   /**
    * Checks if the order date is not older than today.
    *
    * @param DateTime $orderDate
    * @return bool
    */
   public function validateOrderDate(DateTime $orderDate): bool
   {
      $now = DateTime::from(time());

      if ($orderDate >= $now) {
         return true;
      } else {
         return false;
      }
   }

}