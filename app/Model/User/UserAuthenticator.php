<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

/**
 * Handles the user authentication.
 */
class UserAuthenticator implements Nette\Security\IAuthenticator {

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
    * Authenticates the user and returns Identity object.
    *
    * @param array $credentials email and password credentials.
    * @return Nette\Security\IIdentity
    * @throws Nette\Security\AuthenticationException when user does not exist or password does not match.
    */
   public function authenticate(array $credentials): Nette\Security\IIdentity
   {
      [$email, $password] = $credentials;

      $row = $this->RegisteredUserDao->findByEmail($email);

      if ( ! $row) {
         throw new Nette\Security\AuthenticationException("User not found.");
      }

      if ( ! $this->passwords->verify($password, $row->password))  {
         throw new Nette\Security\AuthenticationException("Invalid password.");
      }

      if ($this->passwords->needsRehash($row->password)) {
         $this->RegisteredUserDao->updatePassword($row->id, $this->passwords->hash($password));
      }

      return new Nette\Security\Identity($row->id, null, [
         "email" => $row->email, 
         "fullName" => $row->first_name . " " . $row->surname, 
         "account_status" => $row->account_status
      ]);
   }
}