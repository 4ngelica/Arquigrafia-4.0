<?php

namespace App\Http\Controllers\Chat;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Artdarek\Pusherer\Facades\Pusherer;
use App\lib\log\EventLogger;
use App\Http\Controllers\Controller;
use App\Models\Users\User;

class MessagesController extends Controller {
	public function store(Request $request) {
		if(!Auth::check())
			return \Response::json('false');
		// Saving message
		$input = $request->all();
		$id = $input['user_id'];
		$message = new Message();
		$message->thread_id = $input['thread_id'];
		$message->body = $input['message'];
		$message->user_id = $id;
		$message->save();


		// Getting current user data
		$user = User::find($id);

		// Find thread that we wanna send the message
		$thread = Thread::find($message->thread_id);
		$thread->markAsRead($id);
		// Getting all participants from that thread
		$participants = $thread->participants()->get();
		// Sending pusherer events
		foreach($participants as $participant) {
			// If the participant is the logged user, don't sender the Pusher event
			if($participant->user_id == $id)
				continue;
			// Sending Pusher event
			Pusherer::trigger(strval($participant->user_id), 'new_message', array( 'thread_id' => $thread->id, 'message' => $message, 'user_id' => $id ));;
		}
		$thread->markAsRead($user->id);
		EventLogger::printEventLogs(null, 'new_message', ['thread' => $thread->id, 'message' => $message->id], 'Web');
	}

	public function index(Request $request){
		if(!Auth::check())
			return \Response::json('false');
		$input =$request->all();
		$id = $input['thread_id'];
		$thread = Thread::find($id);
		$messages = $thread->messages()->get();
		return \Response::json($messages);
	}

}
