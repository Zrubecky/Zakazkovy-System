<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserPasswordChanger;
use App\Model\PasswordVerificationException;

/**
 * Handles user password change via the user settings.
 */
class ChangePasswordPresenter extends BasePresenter
{
   /** @var UserPasswordChanger */
   private $userPasswordChanger;
   
   public function __construct(UserPasswordChanger $userPasswordChanger)
   {
      $this->userPasswordChanger = $userPasswordChanger;
   }

   public function startup(): void 
   {
      parent::startup();
      
      if ( ! $this->getUser()->isLoggedIn()) {
         $this->flashMessage("Prosím přihlašte se.", "alert-warning");
         $this->redirect("Sign:in");
      }
   }

   /**
    * factory for change password form.
    *
    * @return Form
    */
   protected function createComponentChangePassForm(): Form 
   {
      $form = new Form;

      $form->addPassword("currentPassword", "Heslo")
         ->setRequired("Prosím vyplňte současné heslo.")
         ->setAttribute("placeholder", "Současné heslo");

      $form->addPassword("newPassword", "Heslo")
         ->setRequired("Prosím vyplňte nové heslo.")
         ->setAttribute("placeholder", "Nové heslo")
         ->addRule(Form::MIN_LENGTH, "Nové Heslo musí obsahovat alespoň %d znaků", 7)
         ->addRule(Form::NOT_EQUAL, "Nové heslo nesmí být shodné s původním.", $form["currentPassword"]);

      $form->addPassword("newPasswordVerify", "Heslo")
         ->setRequired("Prosím vyplňte nové heslo znovu.")
         ->setAttribute("placeholder", "Nové heslo znovu")
         ->addRule(Form::EQUAL, "Hesla se neshodují.", $form["newPassword"]);
         
      $form->addSubmit("send", "Změnit heslo");

      $form->addProtection();

      $form->onSuccess[] = [$this, "changePassFormSucceeded"];

      return $form;
   }


   /**
    * Changes the user password if the request is valid.
    *
    * @param Form $form
    * @param \stdClass $values
    * @return void
    */
   public function changePassFormSucceeded(Form $form, \stdClass $values): void 
   {
      if ( ! $this->getUser()->isLoggedIn()) {
         $this->flashMessage("Prosím přihlašte se.", "alert-warning");
         $this->redirect("Sign:in");
      }

      try {
         $updated = $this->userPasswordChanger->changePassword($this->user->getId(), $values->currentPassword, $values->newPassword);

         if ($updated) {
            $this->flashMessage("Heslo bylo úspěšně změněno.", "alert-success");
         } else {
            $this->flashMessage("Něco se pokazilo, heslo nebyl změněno, zkuste to prosím později.", "alert-danger");
         }

      } catch(PasswordVerificationException $e) {
         $form->addError("Nesprávné současné heslo.");
      }
   }
}
