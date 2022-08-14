<?php

namespace modules\logs\controllers;

use lib\log\EventLogger;

class LogsController extends \BaseController {
  /**
   * This function creates a log, as input, receives
   * @input  {String}  photo_id  (Optional)  If the log belongs to a photoId
   * @input  {String}  class  The class of the log. (See on EventLogger.php)
   * @input  {Object}  payload  The object that stores some information that you wanna record
   */
	public function create() {
		// Getting inputs
		$input = \Input::all();
    $photoId = null;
    if (isset($input['photo_id'])) {
      $photoId = $input['photo_id'];
    }
		$payload = $input['payload'];
    $class = $input['class'];

		// Printing log
		EventLogger::printEventLogs($photoId, $class, $payload, 'Web');
		
		return \Response::json((object)['logged' => true]);
	}
}

