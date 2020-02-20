<?php 

declare(strict_types=1);

namespace App\Model\User;

use App\Model\Exceptions\TokenVerificationException;
use Nette;
use Nette\Utils\DateTime;

/**
 * Handles reset token validation.
 */
class ResetTokenValidator {
   use Nette\SmartObject;

   /** @var ResetTokenDao */
   private $ResetTokenDao;
  

   public function __construct(ResetTokenDao $ResetTokenDao)
   {
      $this->ResetTokenDao = $ResetTokenDao;
   }


   /**
    * Checks if the reset token is valid.
    *
    * @param string $resetToken
    * @return bool
    */
   public function resetTokenValid(string $resetToken): bool
   {
      $tokenRow = $this->ResetTokenDao->findByResetToken($resetToken);

      if ( ! $tokenRow) {
         throw new TokenVerificationException("Token does not exist.");
      }

      if ( ! $this->creationDateValid(DateTime::from($tokenRow["created_at"]))) {
         return false;
      } else {
         return true;
      }

   }

   
   /**
    * Cheks if the reset token is not older than 15 minutes.
    *
    * @param DateTime $creationDate
    * @return bool
    */
   protected function creationDateValid(DateTime $creationDate): bool
   {
      $now = DateTime::from(time());
      $thresholdDate = $creationDate->modify("+ 15 minutes"); 

      return $now > $thresholdDate ? false : true;

   }

}