<?php 

namespace App\Model;

use Nette;
use Nette\Http\FileUpload;
use Nette\Utils\Random;
use App\Model\AttachmentDao;

/**
 * Handles attachment storage into the database and on the server.
 */
class AttachmentStorage {
   use Nette\SmartObject;

   /** @var string */
   private $dir;

   /** @var AttachmentDao */
   private $attachmentDao;
 
   
   public function __construct(string $dir, AttachmentDao $attachmentDao)
   {
      $this->dir = $dir;
      $this->attachmentDao = $attachmentDao;
   }


   /**
    * Handles saving multiple attachments. 
    *
    * @param iterable $files iterable of FileUpload objects.
    * @param integer $orderId
    * @return array corectly saved attachments.
    */
   public function saveMultiple(iterable $files, int $orderId): array
   {
      $attachments = array();

      foreach ($files as $file) {
         $attachment = $this->save($file, $orderId);

         if ($attachment) {
            array_push($attachments, $attachment);
         }
      }

      return $attachments;
   }


   /**
    * Saves single attachment into the database and on ther server.
    *
    * @param FileUpload $file
    * @param integer $orderId
    * @return ActiveRow|bool
    */
   public function save(FileUpload $file, int $orderId)
   {
      if ($file->isOk()) {
         $newName = $this->getNewName();
         $path = $this->dir . $newName;

         try {
            $file->move($path);

         } catch(Nette\InvalidStateException $e) {
            return false;
         }

         $attachment = $this->attachmentDao->save([
            "name" => $file->getName(),
            "order_id" => $orderId,
            "extension" => $this->getExtension($file),
            "attachment_path" => $path
         ]);
         
      }

      return $attachment;
   }


   /**
    * Returns the file extension.
    *
    * @param FileUpload $file file to upload.
    * @return string extension
    */
   public function getExtension(FileUpload $file): string
   {
      return pathinfo($file->getName(), PATHINFO_EXTENSION);
   }

   
   /**
    * Returns new name extracted from the original file name.
    *
    * @param FileUpload $file
    * @return string
    */
   public function getNewName(): string
   {
      return sprintf("%s.%s", Random::generate(64), "tmp");
   }

}