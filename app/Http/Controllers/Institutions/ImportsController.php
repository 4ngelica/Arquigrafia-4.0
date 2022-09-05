<?php

namespace App\Http\Controllers\Institutions;

use App\Models\Institutions\Institution;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\Models\Photos\Photo;
use App\Models\Users\User;
use Carbon\Carbon;
use Image;
use Queue;

class ImportsController extends Controller {

  public function import() {
    $acervoreview = User::where('login','acervoreview')->first();
    if ( ! $acervoreview->equal( Auth::user() ) ) {
      return \Redirect::to('/home');
    }

    $root = public_path() . '/arquigrafia-imports/';

    $acervofau = User::where('login','acervofau')->first();
    if($acervofau){
      $this->importOdsFiles($acervofau, $root . 'acervofau');
    }

    $acervoquapa = User::where('login','acervoquapa')->first();
    if($acervoquapa){
      $this->importOdsFiles($acervofau, $root . 'acervoquapa');
    }

    return \Redirect::to('/home');
  }

  public function importOdsFiles($user, $root) {
    Queue::push( 'App\lib\photoimport\import\Importer', array(
      'user' => $user->id,
      'root' => $root
    ));
  }


}
