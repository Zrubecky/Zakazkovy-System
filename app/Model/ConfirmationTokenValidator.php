<?php 

namespace App\Model;

use Nette;
use App\Model\ConfirmationTokenDao;

/**
 * Handles the confirmation token validation.
 */
class ConfirmationTokenValidator {
   use Nette\SmartObject;

   /** @var ConfirmationTokenDao */
   private $confirmationTokenDao;
  
   
   public function __construct(ConfirmationTokenDao $confirmationTokenDao)
   {
      $this->confirmationTokenDao = $confirmationTokenDao;
   }

   
   /**
    * Validates if the token is stored in the database.
    *
    * @param string $resetToken
    * @return boolean
    */
   public function confirmationTokenValid(string $resetToken): bool
   {
      $tokenRow = $this->confirmationTokenDao->findByToken($resetToken);

      if ( ! $tokenRow) {
         return false;
      } else {
         return true;
      }
   }
}