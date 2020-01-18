<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Utils\Random;
use Nette\database\Table\ActiveRow;
use App\Model\ResetTokenDao;

/**
 * Handles reset token generation.
 */
class ResetTokenGenerator {
   use Nette\SmartObject;

   /** @var ResetTokenDao */
   private $resetTokenDao;


   public function __construct(ResetTokenDao $resetTokenDao)
   {
      $this->resetTokenDao = $resetTokenDao;
   }

   
   /**
    * Generates new reset token into the database and returns it
    *
    * @param integer $userId
    * @return ActiveRow|null
    */
   public function generateToken(int $userId): ?ActiveRow
   {
      $resetToken = Random::generate(32);

      return $this->resetTokenDao->insertToken($userId, $resetToken);
   }

}