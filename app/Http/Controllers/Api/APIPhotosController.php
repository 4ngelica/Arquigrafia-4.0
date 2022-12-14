<?php

namespace App\Http\Controllers\Api;

use App\Models\Photos\Photo;
use App\Models\Users\User;
use App\Models\Photos\Author;
use App\lib\log\EventLogger;
use Date;
use App\Models\Collaborative\Tag;
use App\Models\Institutions\Institution;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;
use App\Models\Collaborative\Comment;
use Auth;
use Cache;

class APIPhotosController extends Controller {


	protected $date;

	public function __construct(Date $date = null)
	{
	    $this->date = $date ?: new Date;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		$result = Cache::remember('index_photos', 60 * 5, function() {
			return Photo::where('draft', null)->get()->toArray();
		});

		return \Response::json($result);
	}

	/**
	 * Display a random listing of the resource.
	 *
	 * @return Response
	 */
	public function randomIndex($count) {

		$photos = Photo::all()->random($count)->toArray();
		return response($photos);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		/* Validação do input */
		$input = $request->all();

		$rules = array(
			'photo_name' => 'required',
	        'photo_imageAuthor' => 'required',
	        'tags' => 'required',
	        'photo_country' => 'required',
	        'authorized' => 'required',
	        'photo' => 'max:10240|required|mimes:jpeg,jpg,png,gif',
	        'photo_imageDate' => 'date_format:d/m/Y|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/'
      	);
		$validator = \Validator::make($input, $rules);
		if ($validator->fails()) {
			return $validator->messages();
		}

		if ($request->hasFile('photo') and $request->file('photo')->isValid()) {
        	$file = $request->file('photo');

			$photo = new Photo;

	      	if($input["photo_allowCommercialUses"] == "true")
	        	$photo->allowCommercialUses = 'YES';
	        else
	        	$photo->allowCommercialUses = 'NO';

	        $photo->allowModifications = $input["photo_allowModifications"];
	        if( !empty($input["photo_city"]) )
	          $photo->city = $input["photo_city"];
	        $photo->country = $input["photo_country"];
	        if ( !empty($input["photo_description"]) )
	          $photo->description = $input["photo_description"];
	        if ( !empty($input["photo_district"]) )
	          $photo->district = $input["photo_district"];
	        if ( !empty($input["photo_imageAuthor"]) )
	          $photo->imageAuthor = $input["photo_imageAuthor"];
	        $photo->name = $input["photo_name"];
	        if ( !empty($input["photo_state"]) )
	          $photo->state = $input["photo_state"];
	        if ( !empty($input["photo_street"]) )
	          $photo->street = $input["photo_street"];

      		$photo->authorized = $input["authorized"];

	      	if(!empty($input["workDate"])){
               $photo->workdate = $input["workDate"];
               $photo->workDateType = "year";
           	}else{
               $photo->workdate = NULL;
            }


           	if(!empty($input["photo_imageDate"])){
                $photo->dataCriacao = $this->date->formatDate($input["photo_imageDate"]);
                $photo->imageDateType = "date";
            }else{
                $photo->dataCriacao = NULL;
            }
	      	$photo->user_id = $input["user_id"];
	      	$photo->dataUpload = date('Y-m-d H:i:s');
	      	$photo->nome_arquivo = $file->getClientOriginalName();

			$photo->save();



            if ($request->has('work_authors')){

	            $input["work_authors"] = str_replace(array('","'), '";"', $input["work_authors"]);
	            $input["work_authors"] = str_replace(array( '"','[', ']'), '', $input["work_authors"]);
	        }else $input["work_authors"] = '';

			$author = new Author();
            if (!empty($input["work_authors"])) {

                $author->saveAuthors($input["work_authors"],$photo);
            }

			$tags = str_replace("\"", "", $input["tags"]);
			$tags = str_replace("[", "", $tags);
			$tags = str_replace("]", "", $tags);
			$tags_copy = $tags;

			$finalTags = explode(",", $tags);

			$tags = Tag::formatTags($finalTags);
			$tagsSaved = Tag::saveTags($finalTags,$photo);

            if(!$tagsSaved){
                  $photo->forceDelete();
                  $messages = array('tags'=>array('Inserir pelo menos uma tag'));
                  return "Tags error";
            }

			$ext = $file->getClientOriginalExtension();
      		$photo->nome_arquivo = $photo->id.".".$ext;

      		$photo->save();

      		$metadata       = Image::make($request->file('photo'))->exif();
  	        $public_image   = Image::make($request->file('photo'))->encode('jpg', 80);
  	        $original_image = Image::make($request->file('photo'));

  	        $public_image->widen(600)->save(public_path().'/arquigrafia-images/'.$photo->id.'_view.jpg');
	        $public_image->heighten(220)->save(public_path().'/arquigrafia-images/'.$photo->id.'_200h.jpg');
	        $public_image->fit(186, 124)->encode('jpg', 70)->save(public_path().'/arquigrafia-images/'.$photo->id.'_home.jpg');
	        $public_image->fit(32,20)->save(public_path().'/arquigrafia-images/'.$photo->id.'_micro.jpg');
	        $original_image->save(storage_path().'/original-images/'.$photo->id."_original.".strtolower($ext));

	        EventLogger::printEventLogs($photo->id, 'upload', ['user' => $photo->user_id], 'mobile');
	        EventLogger::printEventLogs($photo->id, 'insert_tags',
	        							['tags' => $tags_copy, 'user' => $photo->user_id], 'mobile');
	        return $photo->id;

		}
		return "No image sent";
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  string  $id
	 * @return Response
	 */
	public function show($id)
	{
		$photo = Photo::find($id);

		if(!$photo) {
			return \Response::json(["error" => "Image not found" ], 404);
		}

		$license = Photo::licensePhoto($photo);

		if (!is_null($photo->institution_id)) {
			$sender = $photo->institution;
		} else {
			$sender = $photo->user;
		}

		return \Response::json(["photo" => $photo,  "sender" => $sender], 200);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$photo = Photo::where('_id', $id)->first();
		$input = $request->all();

		if($photo->user_id != $input["user_id"]) {
			return \Response::json(array(
				'code' => 403,
				'message' => 'Usuario nao tem permissao para esta operacao'));
		}

		$rules = array(
	        'photo' => 'max:10240|mimes:jpeg,jpg,png,gif',
	        'photo_imageDate' => 'date_format:d/m/Y|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/'
      	);
		$validator = \Validator::make($input, $rules);
		if ($validator->fails()) {
			return $validator->messages();
		}

        if($input["photo_allowCommercialUses"] == "true")
        	$photo->allowCommercialUses = 'YES';
        else
        	$photo->allowCommercialUses = 'NO';

        $photo->authorized = $input["authorized"];
        $photo->allowModifications = $input["photo_allowModifications"];
        if( !empty($input["photo_city"]) )
          $photo->city = $input["photo_city"];
        $photo->country = $input["photo_country"];
        if ( !empty($input["photo_description"]) )
          $photo->description = $input["photo_description"];
        if ( !empty($input["photo_district"]) )
          $photo->district = $input["photo_district"];
        if ( !empty($input["photo_imageAuthor"]) )
          $photo->imageAuthor = $input["photo_imageAuthor"];
        $photo->name = $input["photo_name"];
        if ( !empty($input["photo_state"]) )
          $photo->state = $input["photo_state"];
        if ( !empty($input["photo_street"]) )
          $photo->street = $input["photo_street"];
      	$photo->user_id = $input["user_id"];
      	$photo->dataUpload = date('Y-m-d H:i:s');

      	if($input['authorized'] == true)
      		$photo->authorized = 1;
      	else
      		$photo->authorized = 0;

      	if(!empty($input["workDate"])){
               $photo->workdate = $input["workDate"];
               $photo->workDateType = "year";
       	}else{
           $photo->workdate = NULL;
        }



       	if(!empty($input["photo_imageDate"])){
            $photo->dataCriacao = $this->date->formatDate($input["photo_imageDate"]);
            $photo->imageDateType = "date";
        }else{
            $photo->dataCriacao = NULL;
        }
      	$photo->touch();
		$photo->save();


		if ($request->has('work_authors')){
            $input["work_authors"] = str_replace(array('","'), '";"', $input["work_authors"]);
            $input["work_authors"] = str_replace(array( '"','[', ']'), '', $input["work_authors"]);
        }else $input["work_authors"] = '';
        $author = new Author();

        if (!empty($input["work_authors"])) {
            $author->updateAuthors($input["work_authors"],$photo);
        }else{
            $author->deleteAuthorPhoto($photo);
        }

		$tags = $input["tags"];
		$tags_copy = "";
		if( !empty($input["tags"])){
			if(is_string($tags)){
				$tags = str_replace("\"", "", $tags);
				$tags = str_replace("[", "", $tags);
				$tags = str_replace("]", "", $tags);
				$tags_copy = $tags;

				$tags = explode(",", $tags);
			}
			else {
				$tags_copy = implode(",", $tags);
			}
			$tags = Tag::formatTags($tags);
			$tagsSaved = Tag::updateTags($tags,$photo);

	        if(!$tagsSaved){
	              $photo->forceDelete();
	              $messages = array('tags'=>array('Inserir pelo menos uma tag'));
	              return "Tags error";
	        }
	    }

        if ($request->hasFile('photo') and $request->file('photo')->isValid()) {
        	$file = $request->file('photo');

			$ext = $file->getClientOriginalExtension();
	  		$photo->nome_arquivo = $photo->id.".".$ext;



	  		$metadata       = \Image::make($request->file('photo'))->exif();
		        $public_image   = \Image::make($request->file('photo'))->encode('jpg', 80);
		        $original_image = \Image::make($request->file('photo'));

		    $public_image->widen(600)->save(public_path().'/arquigrafia-images/'.$photo->id.'_view.jpg');
	        $public_image->heighten(220)->save(public_path().'/arquigrafia-images/'.$photo->id.'_200h.jpg');
	        $public_image->fit(186, 124)->encode('jpg', 70)->save(public_path().'/arquigrafia-images/'.$photo->id.'_home.jpg');
	        $public_image->fit(32,20)->save(public_path().'/arquigrafia-images/'.$photo->id.'_micro.jpg');
	        $original_image->save(storage_path().'/original-images/'.$photo->id."_original.".strtolower($ext));
	        $photo->save();
	    }

	    EventLogger::printEventLogs($photo->id, 'edit', ['user' => $photo->user_id], 'mobile');
	    EventLogger::printEventLogs($photo->id, 'edit_tags',
	    							['tags' => $tags_copy, 'user' => $photo->user_id], 'mobile');
        return $photo->id;


	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$photo = Photo::where('_id', $id)->first();
		$user = User::find($request->input('user_id'));

		if($photo->user_id != $request->input('user_id') || $user->mobile_token != $request->input('token')) {
			return \Response::json(array(
				'code' => 403,
				'message' => 'Usuario nao possui autorizacao para esta operacao.'));
		}

	    foreach($photo->tags as $tag) {
	      $tag->count = $tag->count - 1;
	      $tag->save();
	    }
	    \DB::table('tag_assignments')->where('photo_id', '=', $photo->id)->delete();
	    $photo->delete();

	    EventLogger::printEventLogs($photo->id, 'delete', ['user' => $photo->user_id], 'mobile');
	    return \Response::json(array(
				'code' => 200,
				'message' => 'Operacao realizada com sucesso'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $id
	 * @return Response
	 */
	public function comments($id)
	{
		$comments = Comment::where('photo_id', $id)->get();

		foreach($comments as &$comment){

			if(!$comment->created_at && $comment->postDate){
				$comment->dataUpload = $comment->postDate->toDateTime()->format('d/m/Y');
			}else {
				$comment->dataUpload = date('d/m/Y', strtotime($comment->created_at));

			}

			$user = User::where('_id', $comment['user_id'])->get(['name', 'photo'])->first();
			$comment->user = $user;
		}

		return \Response::json( $comments, 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function commentPhoto(Request $request, $id)
	{
		$input = $request->all();
		$rules = ['text' => 'required'];
		$validator = \Validator::make($input, $rules);
		if ($validator->fails()) {
			 $messages = $validator->messages();
			 return \Response::json('Error', 400);
		} else {
			$comment = ['text' => $input["text"], 'user_id' => $input["user_id"], 'photo_id' => $id];
			$comment = new Comment($comment);
			$photo = Photo::find($id);
			if($photo) {
				$photo->comments()->save($comment);
			}


			$comment->dataUpload = date('d/m/Y', strtotime($comment->created_at));

			$user = User::find($input["user_id"]);
			$comment->user = ['_id' => $user->_id, 'name' => $user->name, 'photo' => $user->photo];

			return \Response::json($comment, 200);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteComment($id)
	{

		$comment = Comment::find($id);
		$comment->delete();

		return \Response::json(['msg' => 'Comentário excluído'], 200);
		}

}
