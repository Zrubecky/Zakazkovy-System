<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

/**
 * Handles user signing in and out of the application.
 */
class SignPresenter extends BasePresenter
{
   /**
    * Sign in form factory.
    *
    * @return Form
    */
   protected function createComponentSignInForm(): Form 
   {
      $form = new Form;

      $form->addText("email", "Email")
         ->setRequired("Prosím vyplňte email.")
         ->setAttribute("placeholder", "Email")
         ->addRule(Form::EMAIL, "Email nemá správný formát.");

      $form->addPassword("password", "Heslo")
         ->setRequired("Prosím vyplňte heslo.")
         ->setAttribute("placeholder", "Heslo");

      $form->addSubmit("send", "Přihlásit");

      $form->onSuccess[] = [$this, "signInFormSucceeded"];

      return $form;
   }


   /**
    * Sign in form callback. Redirects the user after the authentication.
    * Redirects to homepage if successfull.
    *
    * @param Form $form
    * @param \stdClass $values
    * @return void
    */
   public function signInFormSucceeded(Form $form, \stdClass $values): void 
   {
      try {
         $this->getUser()->login($values->email, $values->password);
         $this->redirect("Homepage:");

      } catch (Nette\security\AuthenticationException $e) {
         $form->addError("Nesprávné přihlašovací jméno nebo heslo.");
      }
   }


   /**
    * Redirects the user to homepage if logged in.
    *
    * @return void
    */
   public function actionIn(): void
   {
      if ($this->getUser()->isLoggedIn()) {
         $this->redirect("Homepage:");
      }
   }


   /**
    * Signs the user out.
    *
    * @return void
    */
   public function actionOut(): void 
   {
      if ( ! $this->getUser()->isLoggedIn()) {
         $this->redirect("Sign:in");
      }   

      $this->getUser()->logout();

      $this->flashMessage("Odhlášení bylo úspěšné.", "alert alert-success");
      $this->redirect("Sign:in");
   }
}
