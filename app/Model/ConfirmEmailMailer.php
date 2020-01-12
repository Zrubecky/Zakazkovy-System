<?php 

namespace App\Model;

use Nette;
use App\Model\BaseMailer;

/**
 * Handles registration confirmation email.
 */
class ConfirmEmailMailer extends BaseMailer {
   use Nette\SmartObject;

   
   /**
    * Sends registration confirmation email.
    *
    * @param string $email user email.
    * @param string $confirmationToken
    * @return void
    */
   public function sendConfirmationMail(string $email, string $confirmationToken): void
   {
      $template = $this->createTemplate(__DIR__ . "/templates/confirmEmail.latte");
      $template->confirmationToken = $confirmationToken;

      $mail = new Nette\Mail\Message;

      $mail->setFrom($this->getCompanyEmail())
         ->setSubject("Dokončení Registrace")
         ->addTo($email)
         ->setHtmlBody($template);

      $this->mailer->send($mail);
   }

}