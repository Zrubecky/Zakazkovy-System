<?php 

declare(strict_types=1);

namespace App\Model\User;

use App\Model\Exceptions\PasswordVerificationException;
use Nette;
use Nette\Security\Passwords;

/**
 * Handles user password change.
 */
class UserPasswordChanger {
   use Nette\SmartObject;

   /** @var Passwords */
   private $passwords;

   /** @var RegisteredUserDao */
   private $RegisteredUserDao;


   public function __construct(Passwords $passwords, RegisteredUserDao $RegisteredUserDao)
   {
      $this->passwords = $passwords;
      $this->RegisteredUserDao = $RegisteredUserDao;
   }

   
   /**
    * Changes user password when the change request is valid.
    *
    * @param integer $userId
    * @param string $currentPassword
    * @param string $newPassword
    * @return bool
    * @throws App\Model\PasswordVerificationException when current password does not match the user password.
    */
   public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
   {
      $user = $this->RegisteredUserDao->findbyId($userId);

      if ( ! $this->passwords->verify($currentPassword, $user["password"])) {
         throw new PasswordVerificationException("Invalid password.");
      }

      $updated = $this->RegisteredUserDao->updatePassword($userId, $this->passwords->hash($newPassword));
      
      return $updated > 0 ? true : false;
   }

   

}