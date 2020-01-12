<?php 

namespace App\Model;

use Nette;
use Nette\security\Passwords;
use App\Model\RegisteredUserDao;

/**
 * Handles user password reset.
 */
class UserPasswordReseter {
   use Nette\SmartObject;

   /** @var Passwords */
   private $passwords;

   /** @var RegisteredUserDao */
   private $registeredUserDao;


   public function __construct(Passwords $passwords, RegisteredUserDao $registeredUserDao)
   {
      $this->passwords = $passwords;
      $this->registeredUserDao = $registeredUserDao;
   }


   /**
    * Resets user password without password match validation.
    *
    * @param integer $userId
    * @param string $newPassword
    * @return boolean
    */
   public function resetPassword(int $userId, string $newPassword): bool
   {
      $updated = $this->registeredUserDao->updatePassword($userId, $this->passwords->hash($newPassword));

      if ($updated > 0) {
         return true;
      } else {
         return false;
      }
   }
}