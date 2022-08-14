<?php namespace lib\image;

use Image;
use File;

class ImageManager {

  public function makeImage($file) {
    try {
      return Image::make($file)->encode('jpg', 80);
    } catch (\Exception $e) {
      throw new \Exception("Could not read file: '{$file}'");
    }
  }

  public function getOriginalImageExtension($image) {
    return File::extension($image->basePath());
  }

  public function makeOriginal($image, $prefix, $extension) {
    $image->save($prefix . '_original.' . $extension);
  }

  public function make200h($image, $prefix) {
    $image->heighten(220)->save($prefix . '_200h.jpg');
  }

  public function makeHome($image, $prefix) {
    $image->fit(186, 124)->encode('jpg', 70)->save($prefix . '_home.jpg');
  }

  public function makeView($image, $prefix) {
    $image->widen(600)->save($prefix . '_view.jpg');
  }

  public function makeAll($image, $prefix, $extension) {
    $this->makeOriginal($image, $prefix, $extension);
    $this->makeView($image, $prefix);
    $this->make200h($image, $prefix);
    $this->makeHome($image, $prefix);
  }

  public function find($pattern) {
    $file = $this->getFile($pattern);
    return $this->makeImage($file);
  }

  public function getFile($pattern) {
    $files = File::glob( $pattern );
    if ( empty($files) || ! File::isFile($files[0]) ) {
      throw new \Exception("File pattern not found: {$pattern}");
    }
    return $files[0];
  }

}