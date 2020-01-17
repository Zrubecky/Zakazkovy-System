<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserRegistrator;
use App\Model\RegisteredUserDao;
use App\Model\ConfirmationTokenGenerator;
use App\Model\ConfirmEmailMailer;

/**
 * Handles user registration process.
 */
class RegistrationPresenter extends BasePresenter
{
   /** @var UserRegistrator */
   private $UserRegistrator;

   /** @var RegisteredUserDao */
   private $RegisteredUserDao;

   /** @var ConfirmationTokenGenerator */
   private $confirmationTokenGenerator;

   /** @var ConfirmEmailMailer */
   private $confirmEmailMailer;


   public function __construct(UserRegistrator $UserRegistrator, RegisteredUserDao $registeredUserDao, ConfirmationTokenGenerator $confirmationTokenGenerator, ConfirmEmailMailer $confirmEmailMailer)
   {
      $this->UserRegistrator = $UserRegistrator;
      $this->RegisteredUserDao = $registeredUserDao;
      $this->confirmationTokenGenerator = $confirmationTokenGenerator;
      $this->confirmEmailMailer = $confirmEmailMailer;
   }

   public function startup(): void 
   {
      parent::startup();

      if ($this->getUser()->isLoggedIn()) {
         $this->redirect("Homepage:");
      }
   }


   /**
    * Registration form factory.
    *
    * @return Form
    */
   protected function createComponentRegistrationForm(): Form 
   {
      $form = new Form;

      $form->addText("email", "Email")
         ->setRequired("Prosím vyplňte email.")
         ->setAttribute("placeholder", "Email");
      
      $form->addText("name", "Jméno")
         ->setRequired("Prosím vyplňte jméno.")
         ->setAttribute("placeholder", "Jméno");

      $form->addText("surname", "Příjmení")
         ->setRequired("Prosím vyplňte příjmení.")
         ->setAttribute("placeholder", "Příjmení");

      $form->addText("company", "Název Společnosti")
         ->setRequired("Prosím vyplňte společnost.")
         ->setAttribute("placeholder", "Firma");

      $form->addPassword("password", "Heslo")
         ->setRequired("Prosím vyplňte heslo.")
         ->setAttribute("placeholder", "Heslo")
         ->addRule(Form::MIN_LENGTH, "Heslo musí obsahovat alespoň %d znaků", 7);

      $form->addPassword("passwordVerify", "Zopakovat heslo")
         ->setRequired("Prosím vyplňte heslo pro kontrolu.")
         ->setAttribute("placeholder", "Heslo znovu")
         ->addRule(Form::EQUAL, "Hesla se neshodují.", $form["password"]);

      $form->addSubmit("send", "Registrovat");

      $form->onSuccess[] = [$this, "registrationFormSucceeded"];

      return $form;
   }


   /**
    * Registeres the users if they does not already exist.
    *
    * @param Form $form
    * @param \stdClass $values
    * @return void
    */
   public function registrationFormSucceeded(Form $form, \stdClass $values): void
   {
      if ($this->RegisteredUserDao->findByEmail($values->email)) {
         $form->addError("Uživatel již existuje.");

      } else {
         $user = $this->UserRegistrator->register($values);

         if ($user) {
            $token = $this->confirmationTokenGenerator->generateToken($user->id);

            $this->confirmEmailMailer->sendConfirmationMail($user->email, $token->token);

            $this->flashMessage("Registrace proběhla úspěšně, prosím přihlaste se.", "alert-success");
         } else {
            
            $this->flashMessage("Registrace se nezdařila, zkuste to prosím později.", "alert-danger");
         }
         $this->redirect("Sign:in");

      }

   }
}
