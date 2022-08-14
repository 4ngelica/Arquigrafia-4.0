<?php namespace lib\metadata\facade;

use Illuminate\Support\Facades\Facade;

class Exiv2 extends Facade {

  protected static function getFacadeAccessor() { return 'exiv2'; }

}