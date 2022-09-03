<?php

namespace App\Http\Controllers\Photos;

use lib\log\EventLogger;
use Carbon\Carbon;
use lib\date\Date;
use App\Models\Gamification\Badge;
use App\Models\Institution\Institution as Institution;
use App\Models\Collaborative\Tag as Tag;
use App\Models\Collaborative\Comment as Comment;
use App\Models\Collaborative\Like as Like;
use App\Models\Evaluations\Evaluation as Evaluation;
use App\Models\Evaluations\Binomial as Binomial;

class VideosController extends Controller {
	protected $date;

	public function __construct(Date $date ){
		$this->beforeFilter('auth',
			array( 'except' => ['index', 'show'] ));
		$this->date = $date ?: new Date;
	}

	public function store()
  {
    Input::flashExcept('tags','work_authors');
    $input = Input::all();

    if (Input::has('tags'))
      $input["tags"] = str_replace(array('\'', '"', '[', ']'), '', $input["tags"]);
    else
      $input["tags"] = '';

    if (Input::has('work_authors')){
        $input["work_authors"] = str_replace(array('","'), '";"', $input["work_authors"]);
        $input["work_authors"] = str_replace(array( '"','[', ']'), '', $input["work_authors"]);
    }else $input["work_authors"] = '';


    $rules = array(
      'photo_name' => 'required',
      'photo_imageAuthor' => 'required',
      'tags' => 'required',
      'photo_country' => 'required',
      'photo_authorization_checkbox' => 'required',
      'photo_imageDate' => 'date_format:d/m/Y|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/'
    );

    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
        $messages = $validator->messages();

        return Redirect::to('/photos/upload')->with(['tags' => $input['tags'],
        'decadeInput'=>$input["decade_select"],
        'centuryInput'=>$input["century"],
        'decadeImageInput'=>$input["decade_select_image"],
        'centuryImageInput'=>$input["century_image"] ,
        'work_authors'=>$input["work_authors"]
        ])->withErrors($messages);
    } else {


      $video = new Video();

      if ( !empty($input["photo_aditionalImageComments"]) )
        $video->aditionalImageComments = $input["photo_aditionalImageComments"];
      $video->allowCommercialUses = $input["photo_allowCommercialUses"];
      $video->allowModifications = $input["photo_allowModifications"];
      $video->city = $input["photo_city"];
      $video->country = $input["photo_country"];
      if ( !empty($input["photo_description"]) )
          $video->description = $input["photo_description"];
      if ( !empty($input["photo_district"]) )
          $video->district = $input["photo_district"];
      if ( !empty($input["photo_imageAuthor"]) )
          $video->imageAuthor = $input["photo_imageAuthor"];
      $video->name = $input["photo_name"];
      $video->state = $input["photo_state"];
      if ( !empty($input["photo_street"]) )
          $video->street = $input["photo_street"];

      if(!empty($input["workDate"])){
         $video->workdate = $input["workDate"];
         $video->workDateType = "year";
      }elseif(!empty($input["decade_select"])){
         $video->workdate = $input["decade_select"];
         $video->workDateType = "decade";
      }elseif (!empty($input["century"]) && $input["century"]!="NS") {
         $video->workdate = $input["century"];
         $video->workDateType = "century";
      }else{
         $video->workdate = NULL;
      }

      if(!empty($input["photo_imageDate"])){
          $video->dataCriacao = $this->date->formatDate($input["photo_imageDate"]);
          $video->imageDateType = "date";
      }elseif(!empty($input["decade_select_image"])){
          $video->dataCriacao = $input["decade_select_image"];
          $video->imageDateType = "decade";
      }elseif (!empty($input["century_image"]) && $input["century_image"]!="NS") {
          $video->dataCriacao = $input["century_image"];
          $video->imageDateType = "century";
      }else{
          $video->dataCriacao = NULL;
      }



      $video->user_id = Auth::user()->id;
      $video->dataUpload = date('Y-m-d H:i:s');
      $video->save();

      $tags = explode(',', $input['tags']);

      if (!empty($tags)) {
          $tags = Tag::formatTags($tags);
          $tagsSaved = Tag::saveTags($tags,$video);

          if(!$tagsSaved){
            $video->forceDelete();
            $messages = array('tags'=>array('Inserir pelo menos uma tag'));
            return Redirect::to('/photos/upload')->with(['tags' => $input['tags']])->withErrors($messages);
          }
      }

      $author = new Author();
      if (!empty($input["work_authors"])) {
          $author->saveAuthors($input["work_authors"],$video);
      }

      $input['autoOpenModal'] = 'true';
      $eventContent['tags'] = $input['tags'];
      EventLogger::printEventLogs($video->id, 'upload', NULL,'Web');
      EventLogger::printEventLogs($video->id, 'insert_tags', $eventContent,'Web');


      $input['photoId'] = $video->id;
      $input['dates'] = true;
      $input['dateImage'] = true;

      return Redirect::back()->withInput($input);
    }
  }

  public function destroy($id)
  {
    $video = Video::find($id);
    foreach($video->tags as $tag) {
      $tag->count = $tag->count - 1;
      $tag->save();
    }
    DB::table('tag_assignments')->where('photo_id', '=', $photo->id)->delete();

    $photo->delete();

    EventLogger::printEventLogs($id, "delete", null, "Web");
    return Redirect::to('/users/' . $photo->user_id);
  }
}
