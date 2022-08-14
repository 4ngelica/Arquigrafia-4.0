<?php namespace lib\image\facade;

use Illuminate\Support\Facades\Facade;

class ImageManager extends Facade {

  protected static function getFacadeAccessor() { return 'image_manager'; }

}