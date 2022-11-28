<?php
namespace App\Http\Controllers\Api;

use lib\log\EventLogger;
use App\Models\Evaluations\Evaluation;
use App\Models\Evaluations\Binomial;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class APIEvaluationController extends Controller {

	public function retrieveEvaluation($photoId, $userId) {
		$binomials = Binomial::all()->keyBy('id');
		$evaluations =  Evaluation::where("user_id",
        $userId)->where("photo_id", $photoId)->orderBy("binomial_id", "asc")->get();
        $evaluations = $evaluations->keyBy('binomial_id');
        $result = [];
        foreach ($binomials as $binomial) {
        	if (isset($evaluations[$binomial->id])) {
				array_push($result, ["id" => $binomial->id, "firstOption" => $binomial->firstOption, "secondOption" => $binomial->secondOption, "value" => $evaluations[$binomial->id]->evaluationPosition, "knownArchitecture" => $evaluations[$binomial->id]->knownArchitecture, "areArchitecture" => $evaluations[$binomial->id]->areArchitecture]);
        	}
        	else {
        		array_push($result, ["id" => $binomial->id, "firstOption" => $binomial->firstOption, "secondOption" => $binomial->secondOption, "value" => $binomial->defaultValue, "knownArchitecture" => 'no', "areArchitecture" => 'no']);
        	}
        }
        return \Response::json($result);
	}

	public function storeEvaluation(Request $request, $photoId) {
		// dd(Evaluation::where("user_id", $request->user_id)->where("photo_id", $photoId)->get());
		// dd(User::find($request->user_id)->evaluations);

		return \Response::json(['eu' => User::find($request->user_id)->evaluations]);


		$binomials = Binomial::all();

		// $evaluations = Evaluation::where("user_id", $request->user_id)->where("photo_id", $photoId)->get();


		// dd($evaluations->count());

		// foreach ($evaluations as $post) {
		//     $post->delete();
		// }

		// dd('ok');
		$evaluations = Evaluation::where("user_id", $request->user_id)->where("photo_id", $photoId)->get();

        	foreach ($binomials as $binomial) {

        		$newEvaluation = Evaluation::updateOrCreate(
							[
								'binomial_id' => $binomial->binomial_id,
								'user_id'=> $request->user_id,
								'photo_id'=> $photoId
							],
							[
            		'photo_id'=> $photoId,
            		'evaluationPosition'=> $request->input("binomial_$binomial->binomial_id"),
            		'binomial_id'=> $binomial->binomial_id,
            		'user_id'=> $request->user_id,
            		'knownArchitecture'=>$request->knownArchitecture,
            		'areArchitecture'=>$request->areArchitecture
          		]);

						$newEvaluation->update(['id' => $newEvaluation->_id ]);
         	}

						return \Response::json(Evaluation::where("user_id", $request->user_id)->where("photo_id", $photoId)->get());

        }


    public function averageEvaluationValues($photoId, $userId) {
        $result["binomials"] = Binomial::all()->keyBy('id');
        $result["average"] = Evaluation::average($photoId);
        $evaluations =  Evaluation::where("user_id",
        $userId)->where("photo_id", $photoId)->orderBy("binomial_id", "asc")->get();
        $evaluations = $evaluations->keyBy('binomial_id');
        $result["user_evaluation"] = $evaluations;
        return \Response::json($result);
    }
}
