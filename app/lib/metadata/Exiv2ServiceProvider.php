<?php namespace lib\metadata;

use Illuminate\Support\ServiceProvider;

class Exiv2ServiceProvider extends ServiceProvider {
  public function register()
  {
    $this->app->bind('exiv2', function()
    {
      return new Exiv2Manager;
    });
  }
}