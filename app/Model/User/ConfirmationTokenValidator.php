<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;

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

      return ! empty($tokenRow) ? true : false;
   }
}