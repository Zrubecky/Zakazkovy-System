<?php

namespace App\Presenters;

use App\Model\App\Model\TokenVerificationException;
use App\Model\PasswordResetMailer;
use App\Model\RegisteredUserDao;
use App\Model\ResetTokenDao;
use App\Model\ResetTokenValidator;
use App\Model\UserPasswordReseter;
use Nette\Application\UI\Form;

/**
 * Handles user password reset.
 */
class ResetPasswordPresenter extends BasePresenter
{
   /** @var ResetTokenValidator */
   private $resetTokenValidator;

   /** @var RegisteredUserDao */
   private $registeredUserDao;

   /** @var UserPasswordReseter */
   private $userPasswordReseter;

   /** @var ResetTokenDao */
   private $resetTokenDao;

   /** @var PasswordResetMailer */
   private $passwordResetMailer;

   private $user;

   /** @var string */
   private $resetToken;


   public function __construct(ResetTokenValidator $resetTokenValidator, RegisteredUserDao $registeredUserDao, UserPasswordReseter $userPasswordReseter, ResetTokenDao $resetTokenDao, PasswordResetMailer $passwordResetMailer)
   {
      $this->resetTokenValidator = $resetTokenValidator;
      $this->registeredUserDao = $registeredUserDao;
      $this->userPasswordReseter = $userPasswordReseter;
      $this->resetTokenDao = $resetTokenDao;
      $this->passwordResetMailer = $passwordResetMailer;
   }


   public function startup(): void 
   {
      parent::startup();

      if ($this->getUser()->isLoggedIn()) {
         $this->redirect("Homepage:");
      }
   }


   /**
    * Factory for password reset form.
    *
    * @return Form
    */
   protected function createComponentPasswordResetForm(): Form 
   {
      $form = new Form;

      $form->addPassword("newPassword", "Heslo")
         ->setRequired("Prosím vyplňte nové heslo.")
         ->setAttribute("placeholder", "Nové heslo")
         ->addRule(Form::MIN_LENGTH, "Nové Heslo musí obsahovat alespoň %d znaků", 7);

      $form->addPassword("newPasswordVerify", "Heslo")
         ->setRequired("Prosím vyplňte nové heslo znovu.")
         ->setAttribute("placeholder", "Nové heslo znovu")
         ->addRule(Form::EQUAL, "Hesla se neshodují.", $form["newPassword"]);
         
      $form->addSubmit("send", "Změnit heslo");

      $form->addProtection();

      $form->onSuccess[] = [$this, "passwordResetFormSucceeded"];

      return $form;
   }


   /**
    * Validates user reset token.
    *
    * @param string $resetToken
    * @return void
    */
   public function actionReset(string $resetToken): void 
   {
      try {
         $tokenValid = $this->resetTokenValidator->resetTokenValid($resetToken);
      } catch (TokenVerificationException $e) {
         $this->error("Token pro reset hesla neexistuje.");
      }

      if ($tokenValid) {
         $user = $this->registeredUserDao->findByResetToken($resetToken);

         if ( ! $user) {
            $this->error("Chyba při obnově hesla. Prosím zkuste to znovu.");
         } else {
            $this->user = $user;
            $this->resetToken = $resetToken;
         }

      } else {
         $this->flashMessage("Odkaz pro obnovu hesla již není validní, prosím zažádejte o obnovu znovu.", "alert-danger");
         $this->redirect("ResetPasswordRequest:request");
      }
   }


   /**
    * Resets password and sends confirmation mail.
    *
    * @param Form $form
    * @param \stdClass $values
    * @return void
    */
   public function passwordResetFormSucceeded(Form $form, \stdClass $values): void
   {
      if ($this->presenter->getUser()->isLoggedIn()) {
         $this->presenter->redirect("Homepage:");
      }

      if ( ! $this->resetTokenValidator->resetTokenValid($this->resetToken)) {
         $form->addError("Odkaz pro obnovu hesla již není validní, prosím zažádejte o obnovu znovu.");
      }

      $updated = $this->userPasswordReseter->resetPassword($this->user->id, $values->newPassword);

      if( ! $updated) {
         $form->addError("Heslo se nepodařilo resetovat, zkuste to prosím později.");
      } else {
         $this->passwordResetMailer->sendResetPasswordConfirmationMail($this->user->email);
         $this->resetTokenDao->deleteToken($this->resetToken);

         $this->flashMessage("Heslo bylo úspěšně obnoveno.", "alert-success");
         $this->redirect("Homepage:");
      }
   }
}