<?php

namespace App\Presenters;

use App\model\OrderDao;

/**
 * Main homepage (user order dashboard.)
 */
final class HomepagePresenter extends BasePresenter
{

   /** @var OrderDao */
   private $orderDao;

   public function __construct(OrderDao $orderDao)
   {
      $this->orderDao = $orderDao;
   }

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

      $this->template->orders = $this->orderDao->getOrders($this->getUser()->id);
   }
}
