<?php 

namespace App\Model;

use Nette;
use Nette\database\Table\ActiveRow;

/**
 * Handles user registration.
 */
class UserRegistrator {
   use Nette\SmartObject;

   private $database;
   private $passwords;


   public function __construct(Nette\Database\Context $database, Nette\security\Passwords $passwords)
   {
      $this->database = $database;
      $this->passwords = $passwords;
   }


   /**
    * Registeres new user, hashes user typed password.
    *
    * @param \stdClass $userValues
    * @return ActiveRow
    */
   public function register(\stdClass $userValues): ActiveRow
   {
      $hashedPassword = $this->passwords->hash($userValues->password);

      return $this->database->table("users")->insert([
         "email" => $userValues->email,
         "first_name" => $userValues->name,
         "surname" => $userValues->surname,
         "company" => $userValues->company,
         "password" => $hashedPassword
      ]);
   }
}