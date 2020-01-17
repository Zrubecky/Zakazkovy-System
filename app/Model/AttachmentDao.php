<?php 

namespace App\Model;

use Nette;
use Nette\Database\Context;


/**
 * Data access object for order attachments.
 */
class AttachmentDao
{
   use Nette\SmartObject;

   /** @var Context */
   private $database;

   /** @var string */
   private const ATTACHMENT_TABLE = "order_attachments";


   public function __construct(Context $database)
   {
      $this->database = $database;
   }

  
   /**
    * Saves order attachment into the database.
    *
    * @param array $attachmentData [$column => $value].
    * @return ActiveRow
    */
   public function save(array $attachmentData)
   {
      $attachment = $this->database->table(self::ATTACHMENT_TABLE)->insert($attachmentData);

      return $attachment;
   }

}