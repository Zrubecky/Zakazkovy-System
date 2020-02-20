<?php

namespace App\Presenters;

use App\Model\User\ConfirmationTokenDao;
use App\Model\User\ConfirmationTokenValidator;
use App\Model\User\RegisteredUserDao;
use Nette;

/**
 * Handles user registration email confirmation.
 */
class ConfirmEmailPresenter extends Nette\Application\UI\Presenter
{
   /** @var ConfirmationTokenValidator */
   private $confirmationTokenValidator;

   /** @var RegisteredUserDao */
   private $registeredUserDao;

   /** @var ConfirmationTokenDao */
   private $confirmationTokenDao;


   public function __construct(ConfirmationTokenValidator $confirmationTokenValidator, RegisteredUserDao $registeredUserDao, ConfirmationTokenDao $confirmationTokenDao)
   {
      $this->confirmationTokenValidator = $confirmationTokenValidator;
      $this->registeredUserDao = $registeredUserDao;
      $this->confirmationTokenDao = $confirmationTokenDao;
   }


   /**
    * Confirms the user if all criteria are met and confirmation token is valid.
    *
    * @param string $confirmationToken
    * @return void
    */
   public function actionConfirm(string $confirmationToken): void
   {
      $user = $this->registeredUserDao->findByConfirmationToken($confirmationToken);

      if ( ! $user) {
         $this->error("Chyba při potvrzení registrace.");
      }

      if ( ! $this->confirmationTokenValidator->confirmationTokenValid($confirmationToken)) {
         $this->error("Chyba při potvrzení registrace. Token neexistuje.");
      }

      $this->registeredUserDao->updateAccountStatus($user->id, "A");
      $this->user->identity->account_status = "A";

      $this->confirmationTokenDao->deleteToken($confirmationToken);

      $this->flashMessage("Email byl úspěšně potvrzen.", "alert-success");
      $this->redirect("Sign:in");
   }
}
