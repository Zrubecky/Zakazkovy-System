<?php

namespace App\Presenters;

use Nette;

/**
 * Main homepage (user order dashboard.)
 */
final class HomepagePresenter extends Nette\Application\UI\Presenter
{
   public function startup(): void 
   {
      parent::startup();

      if ( ! $this->getUser()->isLoggedIn()) {
         $this->redirect("Sign:in");
      }
   }


   /**
    * Displays warning for the user if the email is not confirmed.
    *
    * @return void
    */
   public function renderDefault(): void
   {
      if ($this->getUser()->getIdentity()->account_status === "P") {
         $this->flashMessage("K dokončení registrace je nutné potvrdit email.", "alert-warning");
      }
   }
}
