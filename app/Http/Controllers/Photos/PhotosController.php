<?php
//add

namespace App\Http\Controllers\Photos;

use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\lib\log\EventLogger;
use Carbon\Carbon;
use App\Models\Photos\Photo;
use App\Models\Photos\Author;
use Auth;
use App\Models\Users\User;
use App\Models\Gamification\Badge;
use App\Models\Institutions\Institution;
use App\Models\Collaborative\Tag;
use App\Models\Collaborative\TagAssignments;
use App\Models\Collaborative\Comment;
use App\Models\Collaborative\Like;
use App\Models\Evaluations\Evaluation;
use App\Models\Evaluations\Binomial;
use App\Models\Gamification\Gamified;
use App\Models\Moderation\PhotoAttributeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;
use App\lib\date\Date;
use File;
use Illuminate\Support\Facades\Http;
use Cache;

class PhotosController extends Controller {
  protected $date;

  public function __construct(Date $date = null)
  {
    $this->date = $date ?: new Date;
  }

  public function index()
  {
    $photos = Photo::all();
    return view('/photos/index',['photos' => $photos]);
  }

  public function show($id)
  {
    EventLogger::printEventLogs($id, "select_photo", NULL, "Web");

    $photo = Photo::find($id);
    if (!$photo) {
      return redirect('/home');
    }

    $tags = TagAssignments::raw((function($collection) use ($id) {
        return $collection->aggregate([
          [
           '$lookup' => [
              'from' => 'tags',
              'localField' => 'tag_id',
              'foreignField'=> 'id',
              'as' => 'tag'
            ]
         ],
          [
            '$project' => [
              'tag.name' => 1,
            ]
         ]
       ]);
    }))->where('photo_id', $id);

    $user = $photo->user()->first();
    $comments = $photo->comments()->get();
    $tags = $photo->tags;
    $likes = $photo->likes->count();
    $photo->dataUpload = date('d/m/Y', strtotime($photo->created_at));
    $license = json_encode(Photo::licensePhoto($photo));
    $latLng = self::getLatLng($photo);
    $incompleteFields = [];
    foreach ($photo->toArray() as $key => $value) {
      if (!$value) {
        array_push($incompleteFields, $key);
      }
    }

    $suggestionFields = json_encode(Photo::getIncompleteFields($photo));

    if (Auth::user()) {
      $authLike = DB::collection('likes')->where('likable_id', $id)->where('user_id', Auth::user()->_id)->first();

      if($authLike) {
        $authLike = 1;
      }

    }else {
      $authLike = 0;
    }

    if(!$photo->institution){
      $institution = 0;
    }else {
      $institution = Institution::find($photo->institution)->first();
    }

    return view('new_front.photos.show', compact(['photo', 'user', 'comments', 'tags', 'likes', 'authLike', 'latLng', 'license', 'suggestionFields', 'institution']));
  }

  public static function getLatLng($photo){

    $address = [$photo->country, $photo->state, $photo->street, $photo->city, $photo->district];
    $address = (implode('+', $address));
    $address = (str_replace(' ', '', $address));

    $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='. config('general.google_maps_key'));
    $body = json_decode($response->body());
    $latLng = [$body->results[0]->geometry->location->lat, $body->results[0]->geometry->location->lng];

    return json_encode($latLng);
  }

  public function form(Request $request)
  {
    if (Session::has('institutionId') ) {
      return Redirect::to('/home');
    }
    $pageSource = $request->header('referer');
    if(empty($pageSource)) $pageSource = '';
    $tags = null;
    $work_authors = null;
    $centuryInput =  null;
    $decadeInput = null;
    $centuryImageInput = null;
    $decadeImageInput = null;
    $dates = false;
    $dateImage = false;

    if ( Session::has('tags') )
    {
      $tags = Session::pull('tags');
      $tags = explode(',', $tags);
    }

    if ( Session::has('work_authors') )
    {
      $work_authors = Session::pull('work_authors');
      $work_authors = explode(';', $work_authors);
    }

    if ( Session::has('centuryInput') ) {
       $centuryInput = Session::pull('centuryInput');
       $dates = true;
      }
    if ( Session::has('decadeInput') ){
       $decadeInput = Session::pull('decadeInput');
       $dates = true;
     }

     if ( Session::has('centuryImageInput') ) {
       $centuryImageInput = Session::pull('centuryImageInput');
       $dateImage = true;
      }
    if ( Session::has('decadeImageInput') ){
       $decadeImageInput = Session::pull('decadeImageInput');
       $dateImage = true;
     }

    $input['autoOpenModal'] = null;

    return view('/photos/form')->with(['tags'=>$tags,'pageSource'=>$pageSource,
      'user'=>Auth::user(),
      'centuryInput'=> $centuryInput,
      'decadeInput' =>  $decadeInput,
      'centuryImageInput'=> $centuryImageInput,
      'decadeImageInput' =>  $decadeImageInput,
      'autoOpenModal'=>$input['autoOpenModal'],
      'dates' => $dates,
      'dateImage' => $dateImage,
      'work_authors'=>$work_authors,
      'type' => null,
      'video'=> null
      ]);

  }


  public function store(Request $request)
  {
      $input = $request->all();

      if ($request->has('tags'))
        $input["tags"] = str_replace(array('\'', '"', '[', ']'), '', $input["tags"]);
      else
        $input["tags"] = '';

      if ($request->has('work_authors')){
          $input["work_authors"] = str_replace(array('","'), '";"', $input["work_authors"]);
          $input["work_authors"] = str_replace(array( '"','[', ']'), '', $input["work_authors"]);
      }else $input["work_authors"] = '';

      $regexVideo = '/^(https:\/\/www\.youtube\.com\/(embed\/|watch\?v=)\S+|https:\/\/(player\.)?vimeo\.com\/(video\/)?\S+)$/';
      $rules = array(
        'photo_name' => 'required',
        'photo_imageAuthor' => 'required',
        'tags' => 'required',
        'type' => 'required',
        'photo_country' => 'required',
        'photo_authorization_checkbox' => 'required',
        'photo' => 'max:10240|required_without_all:video|mimes:jpeg,jpg,png,gif',
        'photo_imageDate' => 'nullable|date_format:d/m/Y|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
        'video' => array('regex:'.$regexVideo,'required_without_all:photo', 'nullable')
      );

      if($input["type"] == "photo"){
        $input["video"] = null;
      }

      $validator = Validator::make($input, $rules);

      if ($validator->fails()) {
          $messages = $validator->messages();

          return redirect()->to('/photos/upload')->with(['tags' => $input['tags'],
          'decadeInput'=>$input["decade_select"],
          'centuryInput'=>(!empty($input["century"])) ? $input["century"] : null,
          'decadeImageInput'=>$input["decade_select_image"],
          'centuryImageInput'=>(!empty($input["century_image"])) ? $input["century_image"] : null,
          'work_authors'=>$input["work_authors"],
          'type'=> $input["type"],
          'video' => $input["video"]
          ])->withErrors($messages);
      } else {

        if ( ( $request->hasFile('photo') and $request->file('photo')->isValid() ) || !empty($input["video"]) ) {
            $file = $request->file('photo');
            $photo = new Photo();

            if ( !empty($input["photo_aditionalImageComments"]) )
              $photo->aditionalImageComments = $input["photo_aditionalImageComments"];
            $photo->allowCommercialUses = $input["photo_allowCommercialUses"];
            $photo->allowModifications = $input["photo_allowModifications"];
            $photo->city = $input["photo_city"];
            $photo->country = $input["photo_country"];
            $photo->type = $input["type"];
            if ( !empty($input["photo_description"]) )
                $photo->description = $input["photo_description"];
            if ( !empty($input["video"]) )
                $photo->video = $input["video"];
            if ( !empty($input["photo_district"]) )
                $photo->district = $input["photo_district"];
            if ( !empty($input["photo_imageAuthor"]) )
                $photo->imageAuthor = $input["photo_imageAuthor"];
            $photo->name = $input["photo_name"];
            $photo->state = $input["photo_state"];
            if ( !empty($input["photo_street"]) )
                $photo->street = $input["photo_street"];

            if(!empty($input["workDate"])){
               $photo->workdate = $input["workDate"];
               $photo->workDateType = "year";
            }elseif(!empty($input["decade_select"])){
               $photo->workdate = $input["decade_select"];
               $photo->workDateType = "decade";
            }elseif (!empty($input["century"]) && $input["century"]!="NS") {
               $photo->workdate = $input["century"];
               $photo->workDateType = "century";
            }else{
               $photo->workdate = NULL;
            }

            if(!empty($input["photo_imageDate"])){
                $photo->dataCriacao = $this->date->formatDate($input["photo_imageDate"]);
                $photo->imageDateType = "date";
            }elseif(!empty($input["decade_select_image"])){
                $photo->dataCriacao = $input["decade_select_image"];
                $photo->imageDateType = "decade";
            }elseif (!empty($input["century_image"]) && $input["century_image"]!="NS") {
                $photo->dataCriacao = $input["century_image"];
                $photo->imageDateType = "century";
            }else{
                $photo->dataCriacao = NULL;
            }



            $photo->user_id = Auth::user()->id;
            $photo->authorized = true;
            $photo->dataUpload = date('Y-m-d H:i:s');
            $photo->save();


            if ( !empty($input["new_album-name"]) ) {
                $album = Album::create([
                'title' => $input["new_album-name"],
                'description' => "",
                'user' => Auth::user(),
                'cover' => $photo,
                ]);
                if ( $album->isValid() ) {
                  DB::insert('insert into album_elements (album_id, photo_id) values (?, ?)', array($album->id, $photo->id));
                }
            } elseif ( !empty($input["photo_album"]) ) {
                DB::insert('insert into album_elements (album_id, photo_id) values (?, ?)', array($input["photo_album"], $photo->id));
            }


            $tags = explode(',', $input['tags']);

            if (!empty($tags)) {
                $tags = Tag::formatTags($tags);
                $tagsSaved = Tag::saveTags($tags,$photo);

                if(!$tagsSaved){
                  $photo->forceDelete();
                  $messages = array('tags'=>array('Inserir pelo menos uma tag'));
                  return Redirect::to('/photos/upload')->with(['tags' => $input['tags']])->withErrors($messages);
                }
            }

            $author = new Author();
            if (!empty($input["work_authors"])) {
                $author->saveAuthors($input["work_authors"],$photo);
            }

            $input['autoOpenModal'] = 'true';
            $eventContent['tags'] = $input['tags'];
            EventLogger::printEventLogs($photo->id, 'upload', NULL,'Web');
            EventLogger::printEventLogs($photo->id, 'insert_tags', $eventContent,'Web');

            if($input["type"] == "photo") {
              $photo->nome_arquivo = $file->getClientOriginalName();
              $ext = $file->getClientOriginalExtension();
              Photo::fileNamePhoto($photo, $ext);

              if(array_key_exists('rotate', $input))
                $angle = (float)$input['rotate'];
              else
                $angle = 0;
              $metadata       = Image::make($request->file('photo'))->exif();
              $public_image   = Image::make($request->file('photo'))->rotate($angle)->encode('jpg', 80);
              $original_image = Image::make($request->file('photo'))->rotate($angle);

              $public_image->widen(600)->save(public_path().'/arquigrafia-images/'.$photo->id.'_view.jpg');
              $public_image->heighten(220)->save(public_path().'/arquigrafia-images/'.$photo->id.'_200h.jpg');
              $public_image->fit(186, 124)->encode('jpg', 70)->save(public_path().'/arquigrafia-images/'.$photo->id.'_home.jpg');
              $public_image->fit(32,20)->save(public_path().'/arquigrafia-images/'.$photo->id.'_micro.jpg');
              $original_image->save(storage_path().'/original-images/'.$photo->id."_original.".strtolower($ext));

              $photo->saveMetadata(strtolower($ext), $metadata);
            } else {
              $videoUrl = $input['video'];
              $array = Photo::getVideoNameAndFile($videoUrl);

              $photo->video = $array['video'];
              $photo->nome_arquivo = $array['file'];
              $photo->type = "video";
            }
            $photo->save();
            $input['photoId'] = $photo->id;
            $input['dates'] = true;
            $input['dateImage'] = true;

            return redirect()->back()->withInput($input);

        } else {
            $messages = $validator->messages();
            return Redirect::to('/photos/upload')->withErrors($messages);
        }
      }
  }

  public function download($id)
  {
    if (Auth::check()) {
      $photo = Photo::find($id);
      if($photo->authorized) {
        $originalFileExtension = strtolower(substr(strrchr($photo->nome_arquivo, '.'), 1));
        $filename = $id . '_original.' . $originalFileExtension;
        $path = storage_path().'/original-images/'. $filename;


        if( File::exists($path) ) {
          EventLogger::printEventLogs($id,"download",NULL,"Web");

          header('Content-Description: File Transfer');
          header("Content-Disposition: attachment; filename=\"". $filename ."\"");
          header('Content-Type: application/octet-stream');
          header("Content-Transfer-Encoding: binary");
          header("Cache-Control: public");
          readfile($path);
          exit;
        }
      }
      return "Arquivo original n??o encontrado.";
    } else {
      return "Voc?? s?? pode fazer o download se estiver logado, caso tenha usu??rio e senha, fa??a novamente o login.";
    }
  }


  public function edit($id) {
    if (Session::has('institutionId') ) {
      return redirect()->to('/home');
    }
    $photo = Photo::find($id);
    $logged_user = Auth::User();
    $work_authors = null;
    if ($logged_user == null) {
      return Redirect::action('PagesController@home');
    }
    elseif ($logged_user->id == $photo->user_id) {
      if (Session::has('tags'))
      {
        $tags = Session::pull('tags');
        $tags = explode(',', $tags);
      } else {
        $tags = $photo->tags->pluck('name');
      }

      if( Session::has('work_authors'))
      {
        $work_authors = Session::pull('work_authors');
        $work_authors = explode(';', $work_authors);
      } else{
        $work_authors = $photo->authors->pluck('name');
      }

      $dateYear = "";
      $decadeInput = "";
      $centuryInput = "";
      $decadeImageInput = "";
      $centuryImageInput = "";

      if(Session::has('workDate')){
        $dateYear = Session::pull('workDate');
      }elseif($photo->workDateType == "year"){
        $dateYear = $photo->workdate;
      }

      if(Session::has('decadeInput')){
         $decadeInput = Session::pull('decadeInput');
      }elseif ($photo->workDateType == "decade"){
          $decadeInput = $photo->workdate;
      }

      if(Session::has('centuryInput')){
         $centuryInput = Session::pull('centuryInput');
      }elseif($photo->workDateType == "century") {
         $centuryInput = $photo->workdate;
      }

      if(Session::has('decadeImageInput')){
         $decadeImageInput = Session::pull('decadeImageInput');
      }elseif ($photo->imageDateType == "decade"){
         $decadeImageInput = $photo->dataCriacao;
      }

      if(Session::has('centuryImageInput')){
         $centuryImageInput = Session::pull('centuryImageInput');
      }elseif($photo->imageDateType == "century") {
         $centuryImageInput = $photo->dataCriacao;
      }

      return view('photos.edit')
        ->with(['photo' => $photo, 'tags' => $tags,
            'dateYear' => $dateYear,
            'centuryInput'=> $centuryInput,
            'decadeInput' =>  $decadeInput,
            'centuryImageInput'=> $centuryImageInput,
            'decadeImageInput' =>  $decadeImageInput,
            'work_authors' => $work_authors,
            'type' => $photo->type,
            'video' => $photo->video
          ] );
    }
    $tags = $photo->tags;
    return Redirect::action('PagesController@home')->with(['tags'=>$tags]);
  }

  public function update(Request $request,$id) {
      $photo = Photo::find($id);
      $input = $request->all();

      if ($request->has('tags'))
        $input["tags"] = str_replace(array('\'', '"', '[', ']'), '', $input["tags"]);
      else
        $input["tags"] = '';


      Log::info("auth =>".$input["work_authors"] );
      if ($request->has('work_authors') && !empty($input["work_authors"])){

        $input["work_authors"] = str_replace(array('","'), '";"', $input["work_authors"]);
        $input["work_authors"] = str_replace(array( '"','[', ']'), '', $input["work_authors"]);
      }else  $input["work_authors"] = '';

      $workDate = "";
      $decadeInput = "";
      $centuryInput = "";
      $imageDateCreated = "";
      $decadeImageInput = "";
      $centuryImageInput = "";

      if($request->has('photo_imageDate')){
        $imageDateCreated = $input["photo_imageDate"];
      }elseif($request->has('decade_select_image')){
         $decadeImageInput = $input["decade_select_image"];
      }elseif($request->has('century_image')){
         $centuryImageInput = $input["century_image"];
      }

      if($request->has('workDate')){
        $workDate = $input["workDate"];
      }elseif($request->has('decade_select')){
         $decadeInput = $input["decade_select"];
      }elseif($request->has('century')){
         $centuryInput = $input["century"];
      }

      $regexVideo = '/^(https:\/\/www\.youtube\.com\/(embed\/|watch\?v=)\S+|https:\/\/(player\.)?vimeo\.com\/(video\/)?\S+)$/';
      $rules = array(
        'photo_name' => 'required',
        'photo_imageAuthor' => 'required',
        'tags' => 'required',
        'type' => 'required',
        'photo_country' => 'required',
        'photo_imageDate' => 'nullable|date_format:d/m/Y|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',
        'photo' => 'max:10240|mimes:jpeg,jpg,png,gif',
         'video' => array('regex:'.$regexVideo, 'nullable')
          );

      if($input["type"] == "photo"){
        $input["video"] = null;
      }

      $validator = Validator::make($input, $rules);

      if ($validator->fails()) {
        $messages = $validator->messages();
        return redirect()->to('/photos/' . $photo->id . '/edit')->with(['tags' => $input['tags'],
          'decadeInput' => $decadeInput,
          'centuryInput' => $centuryInput,
          'workDate' => $workDate,
          'decadeImageInput'=>$decadeImageInput,
          'centuryImageInput'=>$centuryImageInput,
          'imageDateCreated' => $imageDateCreated,
          'work_authors'=>$input["work_authors"],
          'type'=> $input["type"],
          'video' => $input["video"]
          ])->withErrors($messages);

      } else{

        if ( !empty($input["photo_aditionalImageComments"]) )
            $photo->aditionalImageComments = $input["photo_aditionalImageComments"];
        $photo->allowCommercialUses = $input["photo_allowCommercialUses"];
        $photo->allowModifications = $input["photo_allowModifications"];
        $photo->city = $input["photo_city"];
        $photo->country = $input["photo_country"];
        $photo->type = $input["type"];
        $photo->description = $input["photo_description"];
        $photo->district = $input["photo_district"];
        $photo->imageAuthor = $input["photo_imageAuthor"];
        $photo->name = $input["photo_name"];
        $photo->state = $input["photo_state"];
        $photo->street = $input["photo_street"];

        if(!empty($input["workDate"])){
            $photo->workdate = $input["workDate"];
            $photo->workDateType = "year";
        }elseif(!empty($input["decade_select"])){
            $photo->workdate = $input["decade_select"];
            $photo->workDateType = "decade";
        }elseif (!empty($input["century"]) && $input["century"]!="NS") {
            $photo->workdate = $input["century"];
            $photo->workDateType = "century";
        }else{
            $photo->workdate = NULL;
            $photo->workDateType = NULL;
        }

        if(!empty($input["photo_imageDate"])){
            $photo->dataCriacao = $this->date->formatDate($input["photo_imageDate"]);
            $photo->imageDateType = "date";
        }elseif(!empty($input["decade_select_image"])){
            $photo->dataCriacao = $input["decade_select_image"];
            $photo->imageDateType = "decade";
        }elseif (!empty($input["century_image"]) && $input["century_image"]!="NS") {
            $photo->dataCriacao = $input["century_image"];
            $photo->imageDateType = "century";
        }else{
            $photo->dataCriacao = NULL;
        }


        if ($input["type"] == "video") {
          $videoUrl = $input['video'];
          $array = Photo::getVideoNameAndFile($videoUrl);

          $photo->video = $array['video'];
          $photo->nome_arquivo = $array['file'];

          $photo->type = "video";
        }else{
          if ($request->hasFile('photo') and $request->file('photo')->isValid() and $input["type"] == "photo") {
              $file = $request->file('photo');
              $ext = $file->getClientOriginalExtension();
              $photo->nome_arquivo = $photo->id.".".$ext;
              $photo->type = "photo";
              $photo->video = NULL;
          }
        }
        $photo->touch();
        $photo->save();

        $tags = explode(',', $input['tags']);

        if(!empty($tags)) {
            $tags = Tag::formatTags($tags);
            $tagsSaved = Tag::updateTags($tags,$photo);

            if(!$tagsSaved){
                $photo->forceDelete();
                $messages = array('tags'=>array('Erro nos tags'));
                return Redirect::to("/photos/{$photo->id}/edit")->with([
                    'tags' => $input['tags']])->withErrors($messages);
            }
        }

        $author = new Author();
        if (!empty($input["work_authors"])) {
            $author->updateAuthors($input["work_authors"],$photo);
        }else{
            $author->deleteAuthorPhoto($photo);
        }

        $create = false;
        if ($request->hasFile('photo') and $request->file('photo')->isValid() and $input["type"] == "photo") {
          if(array_key_exists('rotate', $input))
              $angle = (float)$input['rotate'];
          else
              $angle = 0;
          $metadata       = Image::make($request->file('photo'))->exif();
          $public_image   = Image::make($request->file('photo'))->rotate($angle)->encode('jpg', 80);
          $original_image = Image::make($request->file('photo'))->rotate($angle);
          $create = true;
        }elseif ($input["type"] == "photo") {
          list($photo_id, $ext) = explode(".", $photo->nome_arquivo);
          $path                 = storage_path().'/original-images/'.$photo->id.'_original.'.$ext;
          $metadata             = Image::make($path)->exif();

          if (array_key_exists('rotate', $input) and ($input['rotate'] != 0)) {
              $angle = (float)$input['rotate'];
              $public_image   = Image::make($path)->rotate($angle)->encode('jpg', 80);
              $original_image = Image::make($path)->rotate($angle);
              $create = true;
          } else
              $create = false;
        }

        if ($create) {
            $public_image->widen(600)->save(public_path().'/arquigrafia-images/'.$photo->id.'_view.jpg');
            $public_image->heighten(220)->save(public_path().'/arquigrafia-images/'.$photo->id.'_200h.jpg');
            $public_image->fit(186, 124)->encode('jpg', 70)->save(public_path().'/arquigrafia-images/'.$photo->id.'_home.jpg');
            $public_image->fit(32,20)->save(public_path().'/arquigrafia-images/'.$photo->id.'_micro.jpg');
            $original_image->save(storage_path().'/original-images/'.$photo->id."_original.".strtolower($ext));
            $photo->saveMetadata(strtolower($ext), $metadata);
        }
        $eventContent["tags"] = $input['tags'];
        EventLogger::printEventLogs($id, 'edit_photo', null, 'Web');
        EventLogger::printEventLogs($id, 'edit_tags', $eventContent, 'Web');




        return redirect()->to("/photos/{$photo->id}")->with('message', '<strong>Edi????o de informa????es da imagem</strong><br>Dados alterados com sucesso');
      }
  }

  public function destroy($id) {
    $photo = Photo::find($id);
    if($photo){
      foreach($photo->tags as $tag) {
        $tag->count = $tag->count - 1;
        $tag->save();
      }
    }
    DB::table('tag_assignments')->where('photo_id', '=', $photo->id)->delete();

    $photo->delete();

    EventLogger::printEventLogs($id, "delete", null, "Web");
    return redirect()->to('/users/' . $photo->user_id);
  }

  public function showCompleteness() {
    return view('/photos/completeness', []);
  }

  public function getCompletenessPhotos() {
	  $photosObj = Photo::where('accepted', 0)->where('type', '<>', 'video')->whereNull('institution_id')->orderByRaw("RAND()")->take(300)->get()->shuffle();

		return \Response::json((object)[
			'photos' => $photosObj,
		]);
  }
}
