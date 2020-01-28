<?php 

namespace App\Model;

use Nette;

/**
 * Handles sending password reset request and confirmation emails.
 */
class PasswordResetMailer extends BaseMailer {
   use Nette\SmartObject;


   /**
    * Sends password reset request email.
    *
    * @param string $userEmail email to which the token is sent.
    * @param string $resetToken token to reset password.
    * @return void
    */
   public function sendResetPasswordRequestMail(string $userEmail, string $resetToken): void
   {
      $template = $this->createTemplate(__DIR__ . "/templates/passwordResetRequest.latte");

      $template->resetToken = $resetToken;

      $mail = new Nette\Mail\Message;

      $mail->setFrom($this->getCompanyEmail())
         ->setSubject("Obnova Hesla")
         ->addTo($userEmail)
         ->setHtmlBody($template);

      $this->mailer->send($mail);
   }

   
   /**
    * Sends password reset confirmation email.
    *
    * @param string $userEmail
    * @return void
    */
   public function sendResetPasswordConfirmationMail(string $userEmail): void
   {
      $template = $this->createTemplate(__DIR__ . "/templates/passwordResetConfirmation.latte");

      $mail = new Nette\Mail\Message;

      $mail->setFrom($this->getCompanyEmail())
         ->setSubject("Obnova Hesla")
         ->addTo($userEmail)
         ->setHtmlBody($template);

      $this->mailer->send($mail);
   }

}