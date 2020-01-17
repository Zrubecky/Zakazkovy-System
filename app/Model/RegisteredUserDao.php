<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

/**
 * Data access object to registered users.
 */
class RegisteredUserDao {
   
   /** @var Context */
   private $database;

   /** @var string */
   private const USERS_TABLE = "users";


   public function __construct(Context $database)
   {
      $this->database = $database;
   }


   /**
    * Returns user by its id.
    *
    * @param integer $userId
    * @return ActiveRow|null
    */
   public function findbyId(int $userId): ?ActiveRow
   {
      return $this->database->table(self::USERS_TABLE)->get($userId);
   }


   /**
    * Returns user by its email.
    *
    * @param string $email
    * @return ActiveRow|null
    */
   public function findByEmail(string $email): ?ActiveRow
   {
      return $this->database->table(self::USERS_TABLE)->where("email", $email)->fetch();
   }


   /**
    * Returns user by its reset token.
    *
    * @param string $resetToken
    * @return ActiveRow|null
    */
   public function findByResetToken(string $resetToken): ?ActiveRow
   {
      $user = $this->database->table(self::USERS_TABLE)
         ->where(":reset_tokens(user).token LIKE ?", $resetToken)
         ->fetch();

      return $user;
   }


   /**
    * Returns user by its email confirmation token.
    *
    * @param string $confirmationToken
    * @return ActiveRow|null
    */
   public function findByConfirmationToken(string $confirmationToken): ?ActiveRow
   {
      $user = $this->database->table($this::USERS_TABLE)
         ->where(":confirmation_tokens(user).token LIKE ?", $confirmationToken)
         ->fetch();

      return $user;

   }


   /**
    * Updates the user password.
    *
    * @param integer $userId
    * @param string $passwordHash
    * @return int count of updated rows.
    */
   public function updatePassword(int $userId, string $passwordHash): int
   {
      $count = $this->database->table($this::USERS_TABLE)
         ->where("id", $userId)
         ->update(["password" => $passwordHash]);

      return $count;
   }

   
   /**
    * Updates the user account status.
    *
    * @param integer $userId
    * @param string $status
    * @return int count of updated rows.
    */
   public function updateAccountStatus(int $userId, string $status): int 
   {
      $count = $this->database->table($this::USERS_TABLE)
         ->where("id", $userId)
         ->update(["account_status" => $status]);
      
      return $count;
   }
}