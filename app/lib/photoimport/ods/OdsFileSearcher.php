<?php namespace lib\photoimport\ods;

use File;

class OdsFileSearcher {

  public function getAllFiles($root) {
    return File::allFiles($root);
  }

  public function search($root) {
    $ods_files = array();
    foreach( $this->getAllFiles($root) as $file ) {
      if ( $this->isOds($file) && ! $this->logExists($file) ) {            
        $ods_files[] = $this->newOds($file);
      }
    }
    return $ods_files;
  }

  public function isOds($file) {
    return $file->isFile() && $file->getExtension() == 'ods';
  }

  public function logExists($file) {
    $file_log = $file->getPathname() . '.log';
    return File::exists($file_log);
  }

  public function newOds($file) {
    return new OdsFile($file);
  }

}