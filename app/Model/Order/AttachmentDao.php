<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

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
    * @return ActiveRow|null
    */
   public function save(array $attachmentData): ?ActiveRow
   {
      $attachment = $this->database->table(self::ATTACHMENT_TABLE)->insert($attachmentData);

      return ! empty($attachment) ? $attachment : null;
   }

}