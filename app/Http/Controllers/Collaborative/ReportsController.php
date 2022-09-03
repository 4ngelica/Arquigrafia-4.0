<?php

namespace App\Http\Controllers\Collaborative;

use App\Models\Collaborative\Report;
use App\Models\Photos\Photo;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller {

	public function index()
	{
		//$tags = Report::all();
		//return $tags;
	}

	public function reportPhoto(Request $request) {

		// $request->flash();
    $input = $request->all();

		$rules = array(
        'reportTypeData' => 'required',
        'reportType' => 'required',
        '_photo' => 'required'
    );
    $validator = \Validator::make($input, $rules);

	if ($validator->fails()) {
      $messages = $validator->messages();
      return \Redirect::to('/photos/'.$input["_photo"])->withErrors($messages);
    } else {
		$photo_id = $input["_photo"];
        $user = Auth::user();
		$photo = Photo::find($photo_id);
		$reportTypeDataAll =$input["reportTypeData"];
		$reportType =$input["reportType"];
        $comment =$input["reportComment"];
        $reportTypeData = implode(",", array_values($reportTypeDataAll));
		$result = Report::getFirstOrCreate($user, $photo, $reportTypeData, $comment,$reportType);
    	return \Redirect::to('/photos/'.$photo->id)->with('message', '<strong>Denúncia enviada com sucesso</strong>');
    }

	}

	public function showModalReportPhoto($id)
	{
		return \Response::json(view('collaborative.form-report')
			->with(['photo_id' => $id])
			->render());
	}


}
