<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;
use App\Model\ResetTokenGenerator;
use App\Model\PasswordResetMailer;

/**
 * Handles password reset request generation.
 */
class PasswordResetRequestGenerator {
   use Nette\SmartObject;

   /** @var ResetTokenGenerator */
   private $tokenGenerator;

   /** @var passwordResetMailer */
   private $passwordResetMailer;

   
   public function __construct(ResetTokenGenerator $tokenGenerator, PasswordResetMailer $passwordResetMailer)
   {
      $this->tokenGenerator = $tokenGenerator;
      $this->passwordResetMailer = $passwordResetMailer;
   }


   /**
    * Generates password reset email and sends password reset request email.
    *
    * @param string $userEmail
    * @param integer $userId
    * @return void
    */
   public function generatePasswordResetRequest(string $userEmail, int $userId): void
   {
      $token = $this->tokenGenerator->generateToken($userId);

      $this->passwordResetMailer->sendResetPasswordRequestMail($userEmail, $token->token);
   }

}