<?php

namespace App\Http\Controllers\Chat;

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

class ThreadsController extends Controller {
	public function store() {
		if(!Auth::check())
			return \Response::json('false');
		$input = Input::all();
		$user = Auth::user();
		try {
			if(!is_array($input['participants']))
				throw new Exception('Invalid participants format.');
			//validacao 50 participantes
			if(count($input['participants']) >= 49)
				throw new Exception('Participants\' limit exceeded.');
			if(in_array($user->id, $input['participants']))
				throw new Exception('Can\'t create a chat with the same user.');
			//validacao de duplicatas de threads entre 2 usuarios
			if(count($input['participants']) == 1){
				$threads = $user->threads()->get();
				foreach($threads as $try){
					if(count($try->participants()->get()) != 2)
						continue;
					if($try->hasParticipant($input['participants'][0]))
						return \Response::json($try->id);
				}
			}

			$input = Input::all();
			$thread = new Thread();
			$subject = null;
			$thread->save();

			/*
			 * Creating a list of participants, to create a thread
			 */

			// Creating initial participant
			$participant = new Participant();
			$participant->thread_id = $thread->id;
			$participant->user_id = $user->id;
			$participant->last_read = Carbon::now();
			$participant->save();

			// On the input variable, this function receives an array of participants ids
			$participants = $input['participants'];
			// Creating the participants with participants ids array
			$thread->addParticipants($participants);
			// Getting all the participants from the current thread
			$participants = $thread->participants()->with(array('user' => function($query) {
				$query->select('id', 'name', 'lastName', 'photo');
			}))->get();

			foreach($participants as $participant){
				// Getting the chat name
				$names = $thread->participantsString($participant->user_id);

				// Triggering event with Pusherer
				Pusherer::trigger(strval($participant->user_id), 'new_thread', array('thread' => $thread, 'participants' => $participants, 'names' => $names));
			}
			EventLogger::printEventLogs(null, 'new_thread', ['thread' => $thread->id, 'participants' => $participants], 'Web');

			return \Response::json($thread->id);
		} catch (Exception $error) {
			return \Response::json('Erro ao realizar operação.', 500);
		}
	}

	public function update($id){
		if(!Auth::check())
			return \Response::json('false');
		$user = Auth::user();
		$thread = Thread::find($id);
		$input = Input::all();
		$participants = $thread->participants()->get();
		try{
			if(!is_array($input['participants']))
				throw new Exception('Incorrect participants format.');
			else if(count($input['participants']) < 1)
				throw new Exception('Incorrect number of participants.');
			else if ( (count($participants) + count($input['participants']) ) > 49)
				throw new Exception('Incorrect number of participants.');
			else {
				$thread->addParticipants($input['participants']);
				$participants = $thread->participants()->get();
				foreach($input['participants'] as $newParticipant){
					$names = $thread->participantsString($newParticipant);
					Pusherer::trigger(strval($newParticipant), 'new_thread', array('thread' => $thread,
						'participants' => $participants, 'names' => $names));
				}

				$participants = $thread->participants()->with(array('user' => function($query) {
					$query->select('id', 'name', 'lastName', 'photo');
				}))->get();
				$names = $thread->participantsString($user->id);
				$last_message = Message::where('thread_id', $thread->id)->orderBy('id', 'desc')->take(1)->get()->first();

				$data = ['thread' => $thread, 'participants' => $participants, 'names' => $names, 'last_message' => $last_message];
				return \Response::json($data);
			}
		} catch (Exception $error){
			return \Response::json('Erro ao realizar operação.', 500);
		}
	}

	public function destroy($id) {
		$user = Auth::user();
		$input = Input::all();
		$thread = Thread::find($id);
		$participant = Participant::where('user_id', $user->id)->where('thread_id', $thread->id)->first();
		$participant->delete();
		if(count($thread->participants()->get()) < 2)
			$thread->delete();
		return View::make('test', ['output' => 'destroy']);
	}

	public function index() {
		if(!Auth::check())
			return Redirect::to('/users/login');
		$user = Auth::user();
		$threads = $user->threads()->get();
		$array = array();

		for($i = 0; $i < count($threads); $i++) {
			$participants = $threads[$i]->participants()->with(array('user' => function($query) {
				$query->select('id', 'name', 'lastName', 'photo');
			}))->get();
			$names = $threads[$i]->participantsString($user->id);
			$last_message = Message::where('thread_id', $threads[$i]->id)->orderBy('id', 'desc')->take(1)->get()->first();
			array_push($array, ['thread' => $threads[$i], 'participants' => $participants,
									   'names' => $names, 'last_message' => $last_message]);
		}
		return View::make('chats', ['data' => $array, 'user_id' => $user->id, 'user_name' => $user->name]);
	}

	public function show($id) {
		if(!Auth::check())
			return Redirect::to('/users/login');
		$user = Auth::user();
		$threads = $user->threads()->get();
		$array = array();
		$thread = Thread::find($id);

		for($i = 0; $i < count($threads); $i++) {
			$participants = $threads[$i]->participants()->with(array('user' => function($query) {
				$query->select('id', 'name', 'lastName', 'photo');
			}))->get();
			$names = $threads[$i]->participantsString($user->id);
			$last_message = Message::where('thread_id', $threads[$i]->id)->orderBy('id', 'desc')->take(1)->get()->first();
			array_push($array, ['thread' => $threads[$i], 'participants' => $participants,
									   'names' => $names, 'last_message' => $last_message]);
		}
		return View::make('chats', ['data' => $array, 'user_id' => $user->id, 'user_name' => $user->name, 'thread' => $thread->id]);
	}

	public function searchUser(){
		$input = Input::all();
		$text = $input['text'];
		$result = User::where('name', 'LIKE', '%' . $text . '%')->orWhere('lastName',
			'LIKE', '%' . $text . '%')->orderBy('name')->select('id', 'name', 'lastName', 'photo')->get();
		return \Response::json($result);
	}

	public function markThreadAsRead(){
		$user = Auth::user();
		try{
			$input = Input::all();
			$thread = Thread::find($input['thread_id']);
			$thread->markAsRead($user->id);
			return \Response::json(true);
		} catch (Exception $error){
			return \Response::json(false);
		}
	}

	public function cards(){
		EventLogger::printEventLogs(null, 'card-chat', null, 'Web');
		Input::flash();
		return \Redirect::to('/chats')->withInput();
	}
}
