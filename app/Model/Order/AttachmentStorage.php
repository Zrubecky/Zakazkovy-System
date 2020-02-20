<?php 

declare(strict_types=1);

namespace App\Model\Order;


use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Http\FileUpload;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;

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
    * Creates attachments directory if it does not exist.
    *
    * @return void
    */
   public function createAttachmentsDir(): void
   {
      FileSystem::createDir($this->dir);
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
      $attachments = [];

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
    * @return ActiveRow|null
    */
   public function save(FileUpload $file, int $orderId): ?ActiveRow
   {
      $this->createAttachmentsDir();

      if ( ! $file->isOk()) {
         return null;
      }

      $newName = $this->getNewName();
      $path = $this->dir . $newName;

      try {
         $file->move($path);
      } catch(Nette\InvalidStateException $e) {
         return null;
      }

      $attachment = $this->attachmentDao->save([
         "name" => $file->getName(),
         "order_id" => $orderId,
         "extension" => $this->getExtension($file),
         "attachment_path" => $path
      ]);

      return ! empty($attachment) ? $attachment : null;
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