<?php

namespace App\Presenters;

use App\Model\PasswordResetRequestGenerator;
use App\Model\RegisteredUserDao;
use Nette\Application\UI\Form;

/**
 * Presenter for password reset request generation.
 */
class ResetPasswordRequestPresenter extends BasePresenter
{
   /** @var PasswordResetRequestGenerator */
   private $passwordResetRequestGenerator;

   /** @var RegisteredUserDao */
   private $registeredUserDao;

   public function __construct(PasswordResetRequestGenerator $passwordResetRequestGenerator, RegisteredUserDao $registeredUserDao)
   {
      $this->passwordResetRequestGenerator = $passwordResetRequestGenerator;
      $this->registeredUserDao = $registeredUserDao;
   }

   public function startup(): void 
   {
      parent::startup();

      if ($this->getUser()->isLoggedIn()) {
         $this->redirect("Homepage:");
      }
   }

   /**
    * Factory for password reset request generation form.
    *
    * @return Form
    */
   protected function createComponentResetPassRequestForm(): Form 
   {
      $form = new Form;

      $form->addText("email", "Email")
         ->setRequired("Prosím vyplňte email.")
         ->setAttribute("placeholder", "Váše emailová adresa")
         ->addRule(Form::EMAIL, "Email nemá správný formát.");

      $form->addSubmit("send", "Obnovit heslo");

      $form->onSuccess[] = [$this, "resetPassRequestFormSucceeded"];

      return $form;
   }

   /**
    * Request password reset token and email generation.
    *
    * @param Form $form
    * @param \stdClass $values
    * @return void
    */
   public function resetPassRequestFormSucceeded(Form $form, \stdClass $values): void
   {
      if ($this->getUser()->isLoggedIn()) {
         $this->redirect("Homepage:");
      }

      $row = $this->registeredUserDao->findByEmail($values->email);

      if ( ! $row) {
         $form->addError("Uživatel neexistuje.");
      } else {
         $this->passwordResetRequestGenerator->generatePasswordResetRequest($values->email, $row->id);

         $this->flashMessage("Email s odkazem na obnovu hesla byl odeslán.", "alert-success");
      }
   }
}

