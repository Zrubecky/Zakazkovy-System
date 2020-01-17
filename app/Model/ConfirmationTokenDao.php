<?php 

namespace App\Model;

use Nette;
use Nette\Database\Context;

/**
 * Data access object for email confirmation tokens.
 */
class ConfirmationTokenDao
{
   use Nette\SmartObject;

   /** @var Context */
   private $database;

   /** @var string */
   private const CONFIRMATION_TOKENS_TABLE = "confirmation_tokens";

   
   public function __construct(Context $database)
   {
      $this->database = $database;
   }


   /**
    * Returns token row by token name.
    *
    * @param string $confirmationToken
    * @return ActiveRow|bool
    */
   public function findByToken(string $confirmationToken)
   {
      return $this->database->table(self::CONFIRMATION_TOKENS_TABLE)->where("token", $confirmationToken)->fetch();
   }


   /**
    * Inserts new token into the database.
    *
    * @param integer $userId User which did generate the token.
    * @param string $confirmationToken
    * @return ActiveRow
    */
   public function insertToken(int $userId, string $confirmationToken)
   {
      return $this->database->table(self::CONFIRMATION_TOKENS_TABLE)->insert([
         "user_id" => $userId,
         "token" => $confirmationToken
      ]);
   }

   
   /**
    * Deletes the token based on token name.
    *
    * @param string $confirmationToken
    * @return int number of deleted rows.
    */
   public function deleteToken(string $confirmationToken): int
   {
      return $this->database->table(self::CONFIRMATION_TOKENS_TABLE)
         ->where("token", $confirmationToken)
         ->delete();
   }
}