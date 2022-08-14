<?php

namespace modules\moderation\controllers;

use lib\log\EventLogger;
use Carbon\Carbon;
use Photo;
use User;
use modules\moderation\models\Suggestion;
use modules\moderation\models\PhotoAttributeType;
use modules\notifications\models\Notification;

class SuggestionsController extends \BaseController {

	public function __construct() {
    // filtering if user is logged out, redirect to login page
    $this->beforefilter('auth',
      array('except' => ['logopenmodal']));
  }

	public function store() {
		$input = \Input::all();

		$rules = array(
			'user_id' => 'required',
			'photo_id' =>'required',
			'attribute_type' => 'required',
			'text' => 'required'
		);

		$validator = \Validator::make($input, $rules);
		if($validator->fails()){
			return \Response::make($validator->messages(), 400);
		}

		$suggestion = new Suggestion();
		// Setting user_id
		$suggestion->user_id = $input['user_id'];
		// Setting photo_id
		$suggestion->photo_id = $input['photo_id'];
		// Setting attribute_type
		$attribute = PhotoAttributeType::where('attribute_type', '=', $input['attribute_type'])->first();
		$suggestion->attribute_type = $attribute->id;
		// Setting text
    if (gettype($input['text']) == 'array') {
      // If the insert text that we're inserting is an array, by convention we separate with ;
      foreach($input['text'] as $textItem) {
        if ($input['text'][0] == $textItem) {
          // If it's the first item, just set textItem as suggestion text
          $suggestion->text = $textItem;
        } else {
          // Separate suggestions with ;
          $suggestion->text = $suggestion->text . '; ' . $textItem;
        }
      }
    } else {
      // If it's not an array, just set the text
		  $suggestion->text = $input['text'];
    }
		// Setting the type
		$currentFieldText = $suggestion->photo[$input['attribute_type']];
		if ($currentFieldText == null) {
			// If there's nothing on the field, it's a edition
			$suggestion->type = 'edition';
		} else {
			// If the user is something on a field, it's a review suggestion
			$suggestion->type = 'review';
		}
    // Saving suggestion
		$suggestion->save();
    // Printing log
		EventLogger::printEventLogs(null, 'completion', ['suggestion' => $suggestion->id], 'Web');

		return \Response::json('Data sent successfully');
	}

  public function sendNotification() {
    $input = \Input::all();
    $user = \Auth::user();
    $photo = Photo::find($input['photo']);
		$owner = $photo->user;
		$points = $input['points'];
		$status = $input['status'];
		$suggestions = $input['suggestions'];

		if($status == 'none') {
			EventLogger::printEventLogs(null, 'completion-none', null, 'Web');
		} else {
			if($status == 'complete') {
				EventLogger::printEventLogs(null, 'completion-complete', ['suggestions' => $suggestions], 'Web');
			}	elseif($status == 'incomplete') {
				EventLogger::printEventLogs(null, 'completion-incomplete', ['suggestions' => $suggestions], 'Web');
			}
			$email = $owner->email;
			if ($points > 0) {
				\Notification::create('suggestionReceived', $user, $photo, [$owner], null);
				\Mail::send('emails.users.suggestion-received', array('name' => $owner->name, 'email' => $owner->email, 'id' => $owner->id, 'user' => $user->name, 'image' => $photo->name),
					function($msg) use($email) {
						$msg->to($email)
							  ->subject('[Arquigrafia]- Recebimento de Sugestão');
					});
		  }

	    $photosObj = Photo::where('accepted', 0)->where('type', '<>', 'video')->whereNull('institution_id')->orderByRaw("RAND()")->take(50)->get()->shuffle();
			$i = 0;
			$photosFiltered = array();
			$i = 0;

			while(count($photosFiltered) < 3 && $i < count($photosObj)){
				if(!$photosObj[$i]->checkPhotoReviewing()){
					$photosFiltered[] = $photosObj[$i];
				}
				$i++;
			}

			$photos = array();
			foreach ($photosFiltered as $photo) {
				$photos[] = ['id' => $photo->id, 'name' => $photo->name, 'nome_arquivo' => $photo->nome_arquivo];
			}
	    return \Response::json($photos);
		}
	}

	public function edit(){
		$user = \Auth::user();
		$photos = $user->photos->lists('id');
		//$photos = [1];
		$suggestions = Suggestion::whereNull('accepted')->whereIn('photo_id', $photos)->get();
		//dd($suggestions);
		$final = [];
		foreach ($suggestions as $suggestion){
			$field = PhotoAttributeType::find($suggestion->attribute_type)->attribute_type;
			$field_name = Photo::$fields_data[$field]['name'];
			$photo = Photo::find($suggestion->photo->id);
			$user = $suggestion->user;
      $field_content = $photo->getFieldContent($field);
			$final[] = ['suggestion' => $suggestion, 'photo' => $photo, 'field' => $field, 'field_name' => $field_name, 'user' => $user, 'field_content' => $field_content];
		}

		return \View::make('show-suggestions', ['suggestions' => $final]);
	}

	public function update(){
		$input = \Input::all();
		$id_self = \Auth::user()->id;
		$suggestion = Suggestion::find($input['suggestion_id']);
		$photo = $suggestion->photo;
		$user = $photo->user;
		$status = $input['operation'];

		if ($status == 'accepted') {
			$field = PhotoAttributeType::find($suggestion->attribute_type)->attribute_type;
			$suggestion->accepted = true;
			$suggestion->save();
			Photo::updateSuggestion($field, $suggestion->text, $suggestion->photo_id);
			if(!$photo->checkPhotoReviewing())
				\Notification::create('suggestionAccepted', $user, $photo, [$suggestion->user], null);
		}
		if ($status == 'rejected') {
			$suggestion->accepted = false;
			$suggestion->save();
			if (!$photo->checkPhotoReviewing())
				\Notification::create('suggestionDenied', $user, $photo, [$suggestion->user], null);
    }

    // Sending email to user
		if (!$photo->checkPhotoReviewing()) {
			// Only send email if the photo is not reviewing
	    $email = $suggestion->user->email;
	    \Mail::send('emails.users.suggestion-analyzed', array('userName' => $suggestion->user->name, 'imageName' => $photo->name),
	      function($msg) use($email) {
	        $msg->to($email)
	          ->subject('[Arquigrafia]- Sugestão Analisada');
	      });
		}

		return \Redirect::to('/users/suggestions');
	}

	/**
	* This function get all the suggestions for a user
	* Inputs:
	* type - Can be 'reviews' or 'editions'. If not pass any type, it gets all the suggestions
  * limit - Number of items per page
  * page - The current page
  * filterId - You can pass a filter, can be 'accepted', 'rejected', 'waiting'
	* @return	A JSON with suggestions
	*/
	public function getUserSuggestions() {
		// Getting paging input
		$input = \Input::all();
		$page = $input['page'];
		$limit = $input['limit'];
    $filterId = $input['filter_id'];
		$skip = ($page - 1) * $limit;
		// Getting the type
		$type = null;
		if (isset($input['type'])) {
			$type = $input['type'];
		}
		// Getting the current user connected
		$id_self = \Auth::user()->id;
		// Getting the suggestions array
		$suggestionsQuery = Suggestion::where('user_id', '=', $id_self)->orderBy('updated_at', 'DESC')->take($limit)->skip($skip);
		// Getting the number of items on total
		$totalItemsQuery = Suggestion::where('user_id', '=', $id_self);

    // Setting filters on suggestionsQuery and totalItemsQuery
    if ($filterId == 'accepted') {
      $suggestionsQuery->where('accepted', '=', '1');
      $totalItemsQuery->where('accepted', '=', '1');
    } else if ($filterId == 'rejected') {
      $suggestionsQuery->where('accepted', '=', '0');
      $totalItemsQuery->where('accepted', '=', '0');
    } else if ($filterId == 'waiting') {
      $suggestionsQuery->where('accepted', '=', null);
      $totalItemsQuery->where('accepted', '=', null);
    }

		// Getting suggestions by type
		if ($type == 'reviews') {
			$suggestions = $suggestionsQuery->where('type', '=', 'review')->get();
			$totalItems = $totalItemsQuery->where('type', '=', 'review')->count();
		} else if ($type == 'editions') {
			$suggestions = $suggestionsQuery->where('type', '=', 'edition')->get();
			$totalItems = $totalItemsQuery->where('type', '=', 'edition')->count();
		} else {
			$suggestions = $suggestionsQuery->get();
			$totalItems = $totalItemsQuery->count();
		}

		// Getting the number of suggestions
		$numSuggestions = count($suggestions);

		// Adding objects suggestions
		foreach ($suggestions as $suggestion) {
			// Adding photo
			$suggestion->photo = Photo::select('id','name','user_id')->find($suggestion->photo_id);
			// Adding user inside photo
			$suggestion->photo->user = User::select('id','name')->find($suggestion->photo->user_id);
			// Adding Field
			$suggestion->field = (object)[];
			$suggestion->field->attribute_type = PhotoAttributeType::find($suggestion->attribute_type)->attribute_type;
			$suggestion->field->name = Photo::$fields_data[$suggestion->field->attribute_type]['name'];
		}

		return \Response::json((object)[
			'suggestions' => $suggestions,
			'current_page' => $page,
			'total_items' => $totalItems,
			'current_num_items' => $numSuggestions
		]);
	}

	/**
	*	This functions gets the statistics about suggestions
	* Inputs:
	* type - Can be 'reviews' or 'editions'. If not pass any type, it gets all the suggestions
	* @return	A JSON with statistics
	*/
	public function getUserSuggestionsStatistics() {
		// Generating input object
		$input = \Input::all();
		// Getting the type
		$type = null;
		if (isset($input['type'])) {
			$type = $input['type'];
		}
		// Getting the current user connected
		$id_self = \Auth::user()->id;
		// Getting the number of suggestions
		$numSuggestionsQuery = Suggestion::where('user_id', '=', $id_self);
		// Number of waiting suggestions
		$numWaitingSuggestionsQuery = Suggestion::where('user_id', '=', $id_self)->whereNull('accepted');
		// Number of accepted suggestions
		$numAcceptedSuggestionsQuery = Suggestion::where('user_id', '=', $id_self)->where('accepted', '=', '1');
		// Number of rejected suggestions
		$numRejectedSuggestionsQuery = Suggestion::where('user_id', '=', $id_self)->where('accepted', '=', '0');
    // Number of points by type
    $numPointsByType = 0;
    $numTotalPoints = 0;

		// Adding the type queries
		if ($type == 'reviews') {
			$numSuggestionsQuery->where('type', '=', 'review');
			$numWaitingSuggestionsQuery->where('type', '=', 'review');
			$numAcceptedSuggestionsQuery->where('type', '=', 'review');
			$numRejectedSuggestionsQuery->where('type', '=', 'review');
      Suggestion::where('user_id', '=', $id_self)->where('type', '=', 'review')->where('accepted', '=', '1')->get()->each(function($suggestion) use(&$numPointsByType) {
        $numPointsByType += $suggestion->numPoints();
      });
		} else if ($type == 'editions') {
			$numSuggestionsQuery->where('type', '=', 'edition');
			$numWaitingSuggestionsQuery->where('type', '=', 'edition');
			$numAcceptedSuggestionsQuery->where('type', '=', 'edition');
			$numRejectedSuggestionsQuery->where('type', '=', 'edition');
      Suggestion::where('user_id', '=', $id_self)->where('type', '=', 'edition')->where('accepted', '=', '1')->get()->each(function($suggestion) use(&$numPointsByType) {
        $numPointsByType += $suggestion->numPoints();
      });
    }

    // Executing queries
    $numSuggestions = $numSuggestionsQuery->count();
    $numWaitingSuggestions = $numWaitingSuggestionsQuery->count();
    $numAcceptedSuggestions = $numAcceptedSuggestionsQuery->count();
    $numRejectedSuggestions = $numRejectedSuggestionsQuery->count();

    // Getting the total number of suggestions
    $numTotalSuggestions = Suggestion::where('user_id', '=', $id_self)->count();
    // Getting the total number of points
    Suggestion::where('user_id', '=', $id_self)->where('accepted', '=', '1')->get()->each(function($suggestion) use (&$numTotalPoints) {
      $numTotalPoints += $suggestion->numPoints();
    });

		return \Response::json((object)[
			'type' => $type,
			'num_suggestions' => $numSuggestions,
			'num_waiting_suggestions' => $numWaitingSuggestions,
			'num_accepted_suggestions' => $numAcceptedSuggestions,
			'num_rejected_suggestions' => $numRejectedSuggestions,
      'num_total_points' => $numTotalPoints,
      'num_points_by_type' => $numPointsByType
		]);
	}
}
