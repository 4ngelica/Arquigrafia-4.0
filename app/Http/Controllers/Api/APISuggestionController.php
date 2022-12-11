<?php
namespace App\Http\Controllers\Api;

use App\Models\Users\User;
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

}
