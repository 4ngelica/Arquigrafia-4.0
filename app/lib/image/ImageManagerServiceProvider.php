<?php namespace lib\image;

use Illuminate\Support\ServiceProvider;

class ImageManagerServiceProvider extends ServiceProvider {
  public function register()
  {
    $this->app->bind('image_manager', function()
    {
      return new ImageManager;
    });
  }
}