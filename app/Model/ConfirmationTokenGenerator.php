<?php 

namespace App\Model;

use Nette;
use Nette\Utils\Random;
use App\Model\ConfirmationTokenDao;
use Nette\database\Table\ActiveRow;

/**
 * Handles confirmation token generation and database insertion.
 */
class ConfirmationTokenGenerator {
   use Nette\SmartObject;

   /** @var ConfirmationTokenDao */
   private $confirmationTokenDao;

   
   public function __construct(ConfirmationTokenDao $confirmationTokenDao)
   {
      $this->confirmationTokenDao = $confirmationTokenDao;
   }

   
   /**
    * Generates the token into the database and returns it.
    *
    * @param integer $userId
    * @return ActiveRow
    */
   public function generateToken(int $userId): ActiveRow
   {
      $confirmationToken = Random::generate(32);

      return $this->confirmationTokenDao->insertToken($userId, $confirmationToken);
   }

}