<?php namespace lib\photoimport\import;

use Photo;
use Tag;
use User;
use lib\photoimport\ods\SheetReader;
use lib\photoimport\ods\OdsFileSearcher;

class Importer {

  protected $tag;

  protected $photo;

  protected $reader;

  protected $logger;

  protected $ods;

  protected $user;

  protected $ods_searcher;

  public function __construct (SheetReader $sr, ImportLogger $logger, OdsFileSearcher $ofs) {
    $this->reader = $sr;
    $this->logger = $logger;
    $this->ods_searcher = $ofs;
  }

  public function init() {
    $this->logger->init('ImportLogger', 'imports');
    $this->logger->logToFile(date('Y-m-d'));
  }

  public function setOds($ods) {
    $this->ods = $ods;
  }

  public function setUser($user) {
    $this->user = $user;
  }

  public function fire($job, $data) {
    $this->init();
    $root = $data['root'];
    $user = User::find( $data['user'] );
    $this->setUser( $user );
    if ( $this->checkUserExists() ) {
      $this->importFromAllFiles($root);
    } else {
      $this->logUndefinedUser();
    }
    $job->delete();
  }

  public function importFromAllFiles($root) {
    $all_files = $this->ods_searcher->search($root);

    foreach ($all_files as $file) {
      $this->setOds($file);
      $this->importFromFile();
    }
  }

  public function checkUserExists() {
    return $this->user instanceof User;
  }

  public function importFromFile() {
    if ( ( $content = $this->getContent() ) == null ) {
      return;
    }
    $this->logStartedToImportFile();
    $photos = $this->importContent($content);
    $this->logFoundPhotos($content);
    $this->logImportedPhotosCount($photos);
  }

  public function importContent($content) {
    $photos = array();
    $ods_basepath = $this->ods->getBasePath();
    foreach ($content as $photo_data) {
      $photo_data['user_id'] = $this->user->id;
      // $photo_data['dataUpload'] = date('Y-m-d H:i:s');
      $tag_data = array_pull( $photo_data, 'tags' );
      $new_photo = $this->import($ods_basepath, $photo_data, $tag_data);
      if ( $new_photo != null ) {
        $photos[] = $new_photo;
      }
    }
    return $photos;
  }

  public function getContent() {
    try {
      return $this->reader->read($this->ods->getPathname());
    } catch (\Exception $e) {
      $this->logOdsReadingException($e, $this->ods);
      return null;
    }
  }

  public function import($basepath, $photo_data, $tag_data) {
    $photo = $this->importPhoto($photo_data, $basepath);
    if ( $photo != null ) {
      $tags = $this->importTags($tag_data);
      $photo->syncTags($tags);
    }
    return $photo;
  }

  public function importPhoto($attributes, $basepath) {
    try {
       $photo = Photo::import($attributes, $basepath);
    } catch (\Exception $e) {
      $this->logPhotoException($e, $attributes['tombo']);
      return null;
    }
    $this->logImportedPhoto($photo);
    return $photo;
  }

  public function importTags($tag_data) {
    $tags = array();
    $raw_tags = Tag::transform($tag_data);
    foreach($raw_tags as $rt) {
      if ( ($tag = $this->getTag($rt)) != null ) {
        $tags[] = $tag;
        // $this->logImportedTag($tag);
      }
    }
    $this->logFoundTags($raw_tags);
    $this->logImportedTagsCount($tags);
    return $tags;
  }

  public function getTag($tag_name) {
    try {
      return Tag::getOrCreate($tag_name);
    } catch (\Exception $e) {
      $this->logTagException($e, $tag_name);
      return null;
    }
  }

  public function logOdsReadingException($exception) {
    $message = "ods_reading_exception: {$this->ods->getPathname()}";
    $message .= ", exception_message: '" . $exception->getMessage() . "'";
    $this->logError($message);
  }

  public function logPhotoException($exception, $photo_tombo) {
    $message = "photo_import_exception: {$photo_tombo}";
    $message .= ", exception_message: '" . $exception->getMessage() . "'";
    $this->logError($message);
  }

  public function logTagException($exception, $tag_name) {
    $message = "tag_import_exception: {$tag_name}";
    $message .= ", exception_message: '{$exception->getMessage()}'";
    $this->logError($message);
  }

  public function logUndefinedUser() {
    $message = "undefined_user: {$this->user}";
    $this->logError($message, false);
  }

  public function logError($message, $logToOds = true) {
    $this->logger->addError($message);
    if ( $logToOds ) {
      $this->ods->logError($message);
    }
  }

  public function logInfo($message, $logToOds = true) {
    $this->logger->addInfo($message);
    if ( $logToOds ) {
      $this->ods->logInfo($message);
    }
  }

  public function logImportedPhoto($photo) {
    $message = "imported_photo: {$photo->tombo}";
    $this->logInfo($message);
  }

  public function logImportedTag($tag) {
    $message = "imported_tag: {$tag->name}";
    $this->logInfo($message);
  }

  public function logStartedToImportFile() {
    $message = "Started to import file '{$this->ods->getPathname()}'";
    $this->logInfo($message, false);
  }

  public function logFoundTags($tags) {
    $message = "total_found_tags: " . count($tags);
    $this->logInfo($message);
  }

  public function logImportedTagsCount($tags) {
    $message = "total_imported_tags: " . count($tags);
    $this->logInfo($message);
  }

  public function logFoundPhotos($photos) {
    $message = "total_found_photos: " . count($photos);
    $this->logInfo($message);
  }

  public function logImportedPhotosCount($photos) {
    $message = "total_imported_photos: " . count($photos);
    $this->logInfo($message);
  }

}