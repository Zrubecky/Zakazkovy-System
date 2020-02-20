<?php 

declare(strict_types=1);

namespace App\Model\User;

use Nette;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

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
    * @return ActiveRow|null
    */
   public function findByToken(string $confirmationToken): ?ActiveRow
   {
      return $this->database->table(self::CONFIRMATION_TOKENS_TABLE)->where("token", $confirmationToken)->fetch();
   }


   /**
    * Inserts new token into the database.
    *
    * @param integer $userId User which did generate the token.
    * @param string $confirmationToken
    * @return ActiveRow|null
    */
   public function insertToken(int $userId, string $confirmationToken): ?ActiveRow
   {
      $token = $this->database->table(self::CONFIRMATION_TOKENS_TABLE)->insert([
         "user_id" => $userId,
         "token" => $confirmationToken
      ]);

      return ! empty($token) ? $token : null;
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