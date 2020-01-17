<?php 

declare(strict_types=1);

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

      return $orderDate >= $now ? true : false;
   }

}