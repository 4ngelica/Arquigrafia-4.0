<?php namespace lib\metadata;

class Exiv2Manager {

  public function saveMetadata($filename, $photo, $metadata) {
    $e = new Exiv2($filename, $photo, $metadata);
    $e->saveMetadata();
  }

}
