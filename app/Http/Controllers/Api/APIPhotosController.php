<?php

namespace App\Http\Controllers\Api;

use App\Models\Photos\Photo;
use App\Models\Users\User;
use App\Models\Photos\Author;
use App\lib\log\EventLogger;
use Date;
use App\Models\Collaborative\Tag;
use App\Models\Institution\Institution;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;
use App\Models\Collaborative\Comment;


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
		return \Response::json(Photo::where('draft', null)->get()->toArray());
		// if ($request->fields) {
		// $fields =	explode(',', $request->fields);
		//	$query = Photo::get($fields);
		// }
		//
		// if ($request->random) {
		// 	$query = $query->random(intval($request->random));
		// }
		//
		// $query = $query->where('draft', null);
		//
		//
		// if ($request->orderBy) {
		// 	$query = $query->paginate(intval($request->paginate));
		// }
		// return Response::json($query);

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

			/* Armazenamento */
			$photo = new Photo;

			//if ( !empty($input["photo_aditionalImageComments"]) )
	        //$photo->aditionalImageComments = $input["photo_aditionalImageComments"];
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
			//Photo e salva para gerar ID automatico

			$ext = $file->getClientOriginalExtension();
      		$photo->nome_arquivo = $photo->id.".".$ext;

      		$photo->save();

      		$metadata       = Image::make($request->file('photo'))->exif();
  	        // $public_image   = \Image::make(\Input::file('photo'))->rotate($angle)->encode('jpg', 80);
  	        // $original_image = \Image::make(\Input::file('photo'))->rotate($angle);
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
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$photo = Photo::where('_id', $id)->first();
		$sender = User::find($photo->user_id);
		$user_id = \Request::get("user_id");
		$tags = $photo->tags->pluck('name');
		if (!is_null($photo->institution_id)) {
			$sender = Institution::find($photo->institution_id);
		}
		$license = Photo::licensePhoto($photo);
		$authorsList = $photo->authors->pluck('name');

		/* Registro de logs */
		EventLogger::printEventLogs($id, 'select_photo', ['user' => $photo->user_id], 'mobile');

		return \Response::json(["photo" => $photo, "sender" => $sender, "license" => $license,
			"authors" => $authorsList, "tags" => $tags]);
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

		//if ( !empty($input["photo_aditionalImageComments"]) )
        //$photo->aditionalImageComments = $input["photo_aditionalImageComments"];
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
		//Photo e salva para gerar ID automatico

        if ($request->hasFile('photo') and $request->file('photo')->isValid()) {
        	$file = $request->file('photo');

			$ext = $file->getClientOriginalExtension();
	  		$photo->nome_arquivo = $photo->id.".".$ext;



	  		$metadata       = \Image::make($request->file('photo'))->exif();
		        // $public_image   = \Image::make($request->file('photo'))->rotate($angle)->encode('jpg', 80);
		        // $original_image = \Image::make($request->file('photo'))->rotate($angle);
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
	public function likes($id)
	{
		$likes = DB::collection('likes')->where('likable_id', $id)->get();

		dd($likes);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $id
	 * @return Response
	 */
	public function comments($id)
	{
		// $comments = DB::collection('comments')->raw((function($collection) {
	   //    return $collection->aggregate([
	   //      [
	   //        '$lookup' => [
	   //          'from' => 'users',
	   //          'localField' => 'user_id',
	   //          'foreignField'=> '_id',
	   //          'as' => 'user'
	   //        ]
	   //      ]
	   //    ]);
		 // }));

		 $comments = Comment::raw((function($collection) {
				 return $collection->aggregate([
					 [
						'$lookup' => [
							 'from' => 'users',
							 'localField' => 'user_id',
							 'foreignField'=> '_id',
							 'as' => 'user'
						 ]
					 ]
				 ]);
		}))->where('photo_id', $id);

		return \Response::json( array_values($comments->toArray()), 200);
	}
}
