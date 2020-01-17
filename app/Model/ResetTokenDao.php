<?php 

namespace App\Model;

use Nette;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

/**
 * Data access object to reset token data.
 */
class ResetTokenDao
{
   use Nette\SmartObject;

   /** @var Context */
   private $database;

   /** @var string */
   private const RESET_TOKENS_TABLE = "reset_tokens";


   public function __construct(Context $database)
   {
      $this->database = $database;
   }


   /**
    * Fetches reset token data by token.
    *
    * @param string $resetToken
    * @return ActiveRow|bool
    */
   public function findByResetToken(string $resetToken)
   {
      return $this->database->table(self::RESET_TOKENS_TABLE)->where("token", $resetToken)->fetch();
   }


   /**
    * Inserts new token into the database.
    *
    * @param integer $userId id of the registered user which generated the token.
    * @param string $resetToken
    * @return ActiveRow
    */
   public function insertToken(int $userId, string $resetToken): ActiveRow
   {
      return $this->database->table(self::RESET_TOKENS_TABLE)->insert([
         "user_id" => $userId,
         "token" => $resetToken
      ]);
   }

   
   /**
    * Deletes the token from the database.
    *
    * @param string $resetToken
    * @return int count of deleted rows.
    */
   public function deleteToken(string $resetToken): int
   {
      return $this->database->table(self::RESET_TOKENS_TABLE)
         ->where("token", $resetToken)
         ->delete();
   }
}