<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use App\Model\OrderTypeDao;
use App\Model\OrderDao;
use App\Model\OrderMailer;
use App\Model\OrderValidator;
use App\Model\AttachmentStorage;

/**
 * Handles creating new user orders.
 */
class OrderPresenter extends BasePresenter
{
   /** @var OrderTypeDao */
   private $orderTypeDao;

   /** @var OrderDao */
   private $orderDao;

   /** @var DateTime */
   private $dateTime;

   /** @var OrderMailer */
   private $orderMailer;

   /** @var OrderDateValidator */
   private $orderValidator;

   /** @var array */
   private $orderTypes;

   /** @var array */
   private $orderTypeNames;

   /** @var AttachmentStorage */
   private $attachmentStorage;


   public function __construct(OrderTypeDao $orderTypeDao, OrderDao $orderDao, OrderMailer $orderMailer, DateTime $dateTime, OrderValidator $orderValidator, AttachmentStorage $attachmentStorage)
   {
      $this->orderTypeDao = $orderTypeDao;
      $this->orderDao = $orderDao;
      $this->dateTime = $dateTime;
      $this->orderMailer = $orderMailer;
      $this->orderValidator = $orderValidator;
      $this->attachmentStorage = $attachmentStorage;
      $this->orderTypeNames = array();
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
    * Prepare order param id for ajax order description changes.
    *
    * @return void
    */
   public function renderPlace() {
      $this->template->orderTypeParamId = $this->getParameterId("typeId");
   }

   public function actionPlace() {
      $this->orderTypes = $this->orderTypeDao->findAllTypes();

      foreach ($this->orderTypes as $type) {
         $this->orderTypeNames[$type["id"]] = $type["type"];
      }
   }

   /**
    * Place order form factory.
    *
    * @return Form
    */
   protected function createComponentPlaceOrderForm(): Form
   {
      $form = new Form;
      
      $form->addText("email", "Email")
         ->setRequired("Prosím vyplňte email.")
         ->setAttribute("placeholder", "Email")
         ->addRule(Form::EMAIL, "Email nemá správný formát.");

      if ($this->getUser()->isLoggedIn()){
         $form["email"]->setDisabled()
            ->setDefaultValue($this->getUser()->getIdentity()->email);     
      }

      $form->addText("name", "Název zakázky")
         ->setRequired("Prosím vyplňte název zakázky")
         ->setAttribute("placeholder", "Název zakázky")
         ->addRule(Form::MIN_LENGTH, "Název zakázky musí mít alespoň %d znaků.", 5);

      if ($this->orderTypes) {
         $form->addSelect("type_id", "Typ zakázky", $this->orderTypeNames)
         ->setDefaultValue(array_key_first($this->orderTypeNames));
      } else {
         $form->addSelect("type_id", "Typ zakázky", [1 => "Jiné"])->setDisabled();
      }

      $form->addText("date", "Termín")
         ->setRequired("Prosím vyplňte termín zakázky.")
         ->setAttribute("placeholder", "dd.mm.yyyy")
         ->addRule(Form::PATTERN, "Termín musí mít validní formát dd.mm.yyyy", "^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$");

      $form->addTextArea("description", "Popis zakázky")
         ->setRequired();
      if ($this->orderTypes){
         $defaultTemplate = $this->orderTypes[array_key_first($this->orderTypeNames)]["template"];
         $form["description"]->setDefaultValue($defaultTemplate);
      }

      $form->addProtection("Vypršel časový limit, odešlete formulář znovu");
      
      $form->addMultiUpload("attachments", "Příloha");

      $form->addSubmit("send", "Odeslat zakázku");

      $form->onSuccess[] = [$this, "placeOrderFormSucceeded"];

      return $form;
   }


   /**
    * Handles ajax update order description link. 
    * Sends json formatted order template.
    *
    * @param int $typeId
    * @return void
    */
   public function handleUpdateOrder($typeId): void
   {
      if ($this->isAjax()) {
         $this->sendJson((object)[
            "orderTemplate" => $this->orderTypes[$typeId]["template"]
         ]);
      }
   }


   /**
    * Handles order processing into the database, on the server and email confirmation.
    *
    * @param Form $form
    * @param \stdClass $values
    * @return void
    */
   public function placeOrderFormSucceeded(Form $form, \stdClass $values): void
   {
      if ( ! $this->getUser()->isLoggedIn()) {
         $this->flashMessage("Prosím přihlašte se.", "alert-warning");
         $this->redirect("Sign:in");
      }

      $date = $this->dateTime::from($values->date);

      if ( ! $this->orderValidator->validateOrderDate($date)) {
         $form->addError("Zadaný termín nesmí být nižší než dnešní datum.");

      } else {
         $order = $this->orderDao->addOrder([
            "user_id" => $this->getUser()->id,
            "name" => $values->name,
            "type_id" => $values->type_id,
            "date" => $date,
            "description" => $values->description
         ]);

         $uploadedAttachments = $this->attachmentStorage->saveMultiple($values->attachments, $order->id);
         
         if ($order && count($uploadedAttachments) === count($values->attachments)) {
            $values->typeName = $this->orderTypeNames[$values->type_id];

            $this->orderMailer->sendOrderMail($this->orderMailer->getCompanyEmail(), $values, $uploadedAttachments);
            $this->orderMailer->sendOrderMail($this->getUser()->getIdentity()->email, $values, $uploadedAttachments);
   
            $this->flashMessage("Zakázka byla úspěšně vytvořena.", "alert alert-success");   
         } else {
            $form->addError("Vznikla chyba a zakázka nebyla vložena do databáze, prosím zkuste to později.");
         }
      }

   }
}
