<?php
namespace App\Http\Controllers\Notifications;
use lib\log\EventLogger;
use App\Models\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Auth;
use Session;
use Request;
use View;

class NotificationsController extends Controller {

	public function show() {
		if (Auth::check()) {
			$user = Auth::user();
			$time_and_date_now = Carbon::now('America/Sao_Paulo');
			foreach ($user->notifications as $notification) {
				$time_and_date_note = Carbon::createFromFormat('Y-m-d H:i:s', $notification->updated_at);
				if ($time_and_date_now->diffInMonths($time_and_date_note) > 1 && $notification->read_at != null) $notification->delete();
			}
			$max_notes = $user->notifications->count();

        	EventLogger::printEventLogs(null, "access_notification_page", null, "Web");
			return View::make('notifications')->with(['user'=>$user, 'max_notes'=>$max_notes]);
		}
		return Redirect::action('PagesController@home');
	}

    public function read($id) {
		if (Auth::check()) {
			$user = Auth::user();
			$note = $user->notifications()->find($id);
			$note->setRead();
			return $user->notifications()->unread()->count();
		}
	}

	public function readAll() {
		if (Auth::check()) {
			$user = Auth::user();
			foreach ($user->notifications as $note) {
				$note->setRead();
			}
			return $user->notifications()->unread()->count();
		}
	}

	public function howManyUnread() {
		if (Auth::check()) {
			$user = Auth::user();
			return $user->notifications()->unread()->count();
		}
	}


}

?>
