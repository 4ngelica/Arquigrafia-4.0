<?php
namespace modules\institutions\controllers;
use modules\institutions\models\Institution;
use Session;  
use Auth;
use Photo;
use Carbon\Carbon;
use Image;
use Queue;

class ImportsController extends \BaseController {

  public function import() {
    $acervoreview = User::whereLogin('acervoreview')->first();
    if ( ! $acervoreview->equal( Auth::user() ) ) {
      return \Redirect::to('/home');
    }
    $root = public_path() . '/arquigrafia-imports/';

    $acervofau = User::whereLogin('acervofau')->first();
    $this->importOdsFiles($acervofau, $root . 'acervofau');

    $acervoquapa = User::whereLogin('acervoquapa')->first();
    $this->importOdsFiles($acervoquapa, $root . 'acervoquapa');
    return \Redirect::to('/home');
  }

  public function importOdsFiles($user, $root) {
    Queue::push( 'lib\photoimport\import\Importer', array(
      'user' => $user->id,
      'root' => $root
    ));
  }


}