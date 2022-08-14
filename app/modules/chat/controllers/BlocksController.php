<?php 

namespace modules\chat\controllers;

use User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Artdarek\Pusherer\Facades\Pusherer;
use lib\log\EventLogger;

class BlocksController extends \BaseController {
	public function create($userId){
		$input = Input::all();
		$target_id = $input['target_id'];
		$blocked = new Block();
		$blocked->user_id = $userId;
		$blocked->blocked_id = $target_id;
		$blocked->save();
	}

	public static function CheckIfBlocked($userId, $targetId) {
		$blocks = User::find($userId)->blocks()->get();
		foreach ($blocks as $block) {
			if($block->blocked_id == $target_id)
				return true;
		}
		return false;
	}
}