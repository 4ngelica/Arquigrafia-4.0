<?php
namespace App\Http\Controllers\Api;

use App\Models\Users\User;
use App\Models\Photos\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Moderation\Suggestion;



class APISuggestionController extends Controller {

	public function storeSuggestion(Request $request, $photoId) {

		if (Suggestion::where('photo_id', $photoId)->count()) {
			return \Response::json('Sugestões bloqueadas', 500);
		}

		$fields = $request->all();

		foreach ($fields as $key => $field) {
			$field = json_decode($field);
			if (is_array($field) && $field[0]) {

				$newSuggestion = Suggestion::create([
					'user_id' => $request->user_id,
					'photo_id' => $photoId,
					'attribute_type' => $field[2],
					'accepted' => null,
					'type' => $field[1],
					'text' => $field[0]
				]);

				$newSuggestion->update(['id' => $newSuggestion->_id ]);
			}

		}

			return \Response::json('Sugestões enviadas', 200);

  }

	public function actionSuggestion(Request $request, $photoId) {

		$fields = $request->all();
		$suggestion = Suggestion::find($fields['suggestion_id']);
		$photo = Photo::find($photoId);

		if(strval($fields['option']) == 1) {
			$photo->update([$fields['field'] => $fields['value']]);
			$suggestion->update(['accepted' => true]);

			return \Response::json('Sugestão aceita', 200);
		}

			$suggestion->update(['accepted' => false]);
			return \Response::json('Sugestão negada', 200);
		}



}
