<?php
namespace modules\collaborative\controllers;
use modules\collaborative\models\Report;
use Photo;
use Auth;

class ReportsController extends \BaseController {

	public function index()
	{
		//$tags = Report::all();
		//return $tags;
	}

	public function reportPhoto() { 
		
	\Input::flash();
    $input = \Input::all();
    
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
		return \Response::json(\View::make('form-report')
			->with(['photo_id' => $id])
			->render());
	}


}
