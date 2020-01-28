<?php 

namespace App\Model;

use Nette;

/**
 * Handles order summary email.
 */
class OrderMailer extends BaseMailer {
   use Nette\SmartObject;


   /**
    * Sends order summary email.
    *
    * @param string $email User email.
    * @param \stdClass $orderParams
    * @param iterable $attachments
    * @return void
    */
   public function sendOrderMail(string $email, \stdClass $orderParams, iterable $attachments = []): void
   {
      $template = $this->createTemplate(__DIR__ . "/templates/orderSummary.latte");

      $template->orderParams = $orderParams;
   
      $mail = new Nette\Mail\Message;

      $mail->setFrom($this->getCompanyEmail())
         ->setSubject("Nová Zakázka")
         ->addTo($email)
         ->setHtmlBody($template);

      if (! empty($attachments)) {
         foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment->name, file_get_contents($attachment->attachment_path));
         }  
      }

      $this->mailer->send($mail);
   }

}