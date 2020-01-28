<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;

/**
 * Base object for all specific mailers.
 * Handles template creation.
 */
class BaseMailer {
   use Nette\SmartObject;

   /** @var string */
   protected $homeAddress;

   /** @var IMailer */
   protected $mailer;

   /** @var LinkGenerator */
   protected $linkGenerator;

   /** @var ITemplateFactory */
   protected $templateFactory;

   
   public function __construct(string $homeAddress, IMailer $mailer, ITemplateFactory $templateFactory, LinkGenerator $linkGenerator)
   {
      $this->mailer = $mailer;
      $this->templateFactory = $templateFactory;
      $this->linkGenerator = $linkGenerator;
      $this->homeAddress = $homeAddress;
   }


   /**
    * Returns company email from which mailers send mail.
    *
    * @return string
    */
   public function getCompanyEmail(): string
   {
      return $this->homeAddress;
   }

   
   /**
    * Returns template object for mailers.
    *
    * @param string $templatePath Path to latte file.
    * @return Nette\Application\UI\ITemplate
    */
   protected function createTemplate(string $templatePath): Nette\Application\UI\ITemplate
   {
      $template = $this->templateFactory->createTemplate();
      $template->getLatte()->addProvider("uiControl", $this->linkGenerator);

      $template->setFile($templatePath);

      return $template;
   }

}