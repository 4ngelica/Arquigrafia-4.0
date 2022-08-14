<?php

namespace App\Models\Photos;

use modules\draft\traits\DraftingTrait;
use lib\date\Date;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;

use modules\gamification\traits\LikableGamificationTrait;
use Illuminate\Database\Eloquent\Collection as Collection;
use modules\institutions\models\Institution;
use modules\collaborative\models\Like as Like;
use modules\evaluations\models\Evaluation as Evaluation;
use modules\moderation\models\Suggestion as Suggestion;

class Photo extends Eloquent {

	// use DraftingTrait;
	// use SoftDeletingTrait;
	// use LikableGamificationTrait;

	protected $softDelete = true;

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'aditionalImageComments',
		'allowCommercialUses',
		'allowModifications',
		'cataloguingTime',
		'characterization',
		'city',
		'collection',
		'country',
		'dataCriacao',
		'dataUpload',
		'description',
		'district',
		'imageAuthor',
		'name',
		'nome_arquivo',
		'state',
		'street',
		'tombo',
		'type',
		'user_id',
		'video',
		'workAuthor',
		'workdate',
		'support',
		'authorized',
		'institution_id',
		'project_author',
	];

	static $allowModificationsList = [
		'YES' => ['Sim', ''],
		'YES_SA' => ['Sim, contanto que os outros compartilhem de forma semelhante', '-sa'],
		'NO' => ['Não', '-nd']
	];

	static $allowCommercialUsesList = [
		'YES' => ['Sim', ''],
		'NO' => ['Não', '-nc']
	];

	public static $fields_data = [
		'city'        => ['information' => 'Qual é a cidade desta obra?',
					      'validation'  => 'Esta cidade está correta?',
					      'name'        => 'Cidade',
					      'type'        => 'string'],
		'country'     => ['information' => 'Qual é o país desta obra?',
						  'validation'  => 'Este país está correto?',
						  'name'        => 'País',
						  'type'        => 'string'],
		'dataCriacao' => ['information' => 'Qual é a data desta imagem?',
						  'validation'  => 'A data desta imagem está correta?',
						  'name'        => 'Data da Imagem',
						  'type'        => 'string'],
		'description' => ['information' => 'Como você descreveria esta imagem?',
						  'validation'  => 'A descrição desta imagem está correta?',
						  'name'        => 'Descrição',
						  'type'        => 'string'],
		'district'    => ['information' => 'Qual é o bairro desta obra?',
						  'validation'  => 'O bairro desta obra está correto?',
						  'name'        => 'Bairro',
						  'type'        => 'string'],
		'imageAuthor' => ['information' => 'Quem é o autor desta imagem?',
						  'validation'  => 'Este é o autor correto desta imagem?',
						  'name'        => 'Autor',
						  'type'        => 'string'],
		'name'        => ['information' => 'Qual é o nome desta obra?',
						  'validation'  => 'Este é o nome correto desta obra?',
						  'name'        => 'Nome',
						  'type'        => 'string'],
		'state'         => ['information' => 'Em qual estado do país está esta arquitetura?',
						  'validation'  => 'Este é o estado correto desta arquitetura?',
						  'name'        => 'Estado',
						  'type'        => 'string'],
		'street'      => ['information' => 'Qual é o endereço desta obra?',
						  'validation'  => 'Este é o endereço correto desta obra?',
						  'name'        => 'Rua',
						  'type'        => 'string'],
		'authors'  => ['information' => 'Qual é o nome do autor deste projeto? (Havendo mais de um, separe por ";")',
						  'validation'  => 'Este é o autor deste projeto?',
						  'name'        => 'Autor do Projeto',
						  'type'        => 'array_strings'],
		'workDate'    => ['information' => 'Quando foi construída esta obra?',
						  'validation'  => 'Esta é a data em que esta obra foi construída?',
						  'name'        => 'Data da Obra',
						  'type'        => 'string']
	];

	// public static	$information_questions = [
	// 	'city' => 'Qual é a cidade da obra?',
	// 	'country' => 'Qual é o país da obra?',
	// 	'dataCriacao' => 'Qual é a data desta imagem?',
	// 	'description' => 'Qual é a descrição para a imagem?',
	// 	'district' => 'Qual é o bairro da obra?',
	// 	'imageAuthor' => 'Quem é o autor desta imagem?',
	// 	'name' => 'Qual é o nome desta obra?',
	// 	'state' => 'Qual é o Estado desta arquitetura?',
	// 	'street' => 'Qual é a rua desta obra?',
	// 	'workAuthor' => 'Quem é o autor da obra?',
	// 	'workDate' => 'Quando foi construída a obra?'
	// ];

	protected $date;

	// public function __construct($attributes = array(), Date $date = null) {
	// 	parent::__construct($attributes);
	// 	$this->date = $date ?: new Date;
	// }

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function institution()
	{
		return $this->belongsTo('modules\institutions\models\Institution');
	}

	public function tags()
	{
		return $this->belongsToMany('modules\collaborative\models\Tag', 'tag_assignments');
	}

	public function authors()
	{
		return $this->belongsToMany('Author', 'photo_author');
	}

	public function comments()
	{
		return $this->hasMany('modules\collaborative\models\Comment');
	}

	public function albums()
	{
		return $this->belongsToMany('Album', 'album_elements');
	}

	public function evaluations()
	{
		return $this->hasMany('modules\evaluations\models\Evaluation');
	}

	public function evaluators()
	{
		return $this->belongsToMany('User', 'binomial_evaluation');
	}

	public function suggestions()
	{
		return $this->hasMany('modules\moderation\models\Suggestion');
	}

	public static function extractVideoId($url){
		$array1 = explode("://", $url);
		$array2 = explode("/", $array1[count($array1) - 1]);
		if( strpos($array2[count($array2) - 1], 'watch') !== false){
			//tem watch
			$array3 = explode("=", $array2[count($array2) - 1]);
			$array4 = explode("&", $array3[1]);
			return $array4[0];
		} else {
			$array3 = explode("?", $array2[count($array2) - 1]);
			return $array3[0];
		}
	}

	public static function getVideoNameAndFile($url) {
		$videoUrl = Photo::extractVideoId($url);
	    if( strpos($url, 'vimeo') !== false ){//vimeo
	    	$video = "https://player.vimeo.com/video/" . $videoUrl;
	        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . $videoUrl . ".php"));
	        $file = $hash[0]['thumbnail_medium'];
	    } else {
	        $video = "https://www.youtube.com/embed/" . $videoUrl;
	        $file = "https://img.youtube.com/vi/" . $videoUrl . "/sddefault.jpg";
	    }

		$final = array('file'  => $file,
					   'video' => $video);

		return $final;
	}

	public function saveMetadata($originalFileExtension, $metadata)
	{
		$original_path = storage_path() . '/original-images/';
		$original_path .= $this->id . '_original.' . $originalFileExtension;
		$view_path = public_path() . '/arquigrafia-images/' . $this->id . '_view.jpg';
		$h200_path = public_path() . '/arquigrafia-images/' . $this->id . '_200h.jpg';
		$home_path = public_path() . '/arquigrafia-images/' . $this->id . '_home.jpg';
		$micro_path = public_path() . '/arquigrafia-images/' . $this->id . '_micro.jpg';
		Exiv2::saveMetadata($original_path, $this, $metadata);
		Exiv2::saveMetadata($view_path, $this, $metadata);
		Exiv2::saveMetadata($h200_path, $this, $metadata);
		Exiv2::saveMetadata($home_path, $this, $metadata);
		Exiv2::saveMetadata($micro_path, $this, $metadata);
	}

	public static function paginateUserPhotos($user, $perPage = 24) {
		return static::withUser($user)
			->withoutInstitutions()->paginate($perPage);
	}

	public static function paginateInstitutionPhotos($institution, $perPage = 24) {
		return static::withInstitution($institution)->paginate($perPage);
	}

	public static function paginateAlbumPhotos($album, $perPage = 24) {
		return $album->photos()->paginate($perPage);
	}

	public static function paginateOtherPhotos($user, $photos, $perPage = 24) {
		if ( Session::has('institutionId') ) {
			return static::withInstitution($user)->except($photos)->paginate($perPage);
		} else {
			return static::withUser($user)->withoutInstitutions()
				->except($photos)->paginate($perPage);
		}
	}

	public static function paginateUserPhotosNotInAlbum($user, $album, $q = null, $perPage = 24) {

		return static::notInAlbum($album, $q)->withUser($user)
			->withoutInstitutions()->paginate($perPage);


	}

	public static function paginateInstitutionPhotosNotInAlbum($inst, $album, $q = null, $perPage = 24) {
		return static::notInAlbum($album, $q)
			->withInstitution($inst)->paginate($perPage);
	}

	public static function paginateAllPhotosNotInAlbum($album, $q = null, $perPage = 24) {
		return static::notInAlbum($album, $q)->paginate($perPage);
	}

	public static function paginateFromAlbumWithQuery($album, $q, $perPage = 24) {
		return static::inAlbum($album, $q)->paginate($perPage);
	}

	public static function composeArchitectureName($name) {
		$array = explode(" ", $name);
		$architectureName = "";
		if (!is_null($array) && !is_null($array[0])) {
			if (ends_with($array[0], 'a') || ends_with($array[0], 'dade')
				|| ends_with($array[0], 'ção') || ends_with($array[0], 'ase')
				|| ends_with($array[0], 'ede') || ends_with($array[0], 'dral')
				|| ends_with($array[0], 'agem') || $array[0] == "fonte"
				|| $array[0] == "Fonte" || $array[0] == "ponte"
				|| $array[0] == "Ponte")
				$architectureName = 'a ';
			else if (ends_with($array[0], 's'))
				$architectureName = 'a arquitetura de ';
			else
				$architectureName = 'o ';
		}
		return $architectureName = $architectureName .$name;
	}

	public static function getEvaluatedPhotosByUser($user) {
		$evaluations = Evaluation::where("user_id", $user->id)->groupBy('photo_id')->distinct()->get();
		return Photo::whereIn('id', $evaluations->lists('photo_id'))->get();
	}


	public static function getLastUpdatePhotoByUser($user_id) {
		return $dataUpdate = Photo::where("user_id", $user_id)->orderBy('updated_at','desc')->first();
	}
	public static function getLastUploadPhotoByUser($user_id) {
		return Photo::where("user_id", $user_id)->orderBy('dataUpload','desc')->first();
	}

	public static function photosWithSimilarEvaluation($average,$idPhotoSelected) {
		Log::info("Logging function Similar evaluation");
		$similarPhotos = array();
		$arrayPhotosId = array();
		$arrayPhotosDB = array();
		$i=0;

		if (!empty($average)) {
			foreach ($average as $avg) {
				Log::info("Logging params ".$avg->binomial_id." ".$avg->avgPosition);
				//average of photo by each binomial(media de fotos x binomio)
				$avgPhotosBinomials = DB::table('binomial_evaluation')
				->select('photo_id', DB::raw('avg(evaluationPosition) as avgPosition'))
				->where('binomial_id', $avg->binomial_id)
				->where('photo_id','<>' ,$idPhotoSelected)
				->groupBy('photo_id')->get();

				//clean array for news id photo
				$arrayPhotosId = array();
				$flag=false;

				foreach ($avgPhotosBinomials as $avgPhotoBinomial) {

					if(abs($avgPhotoBinomial->avgPosition - $avg->avgPosition)<=25){
						$flag=true;
						array_push($arrayPhotosId,$avgPhotoBinomial->photo_id);
					}
				}

				if($flag == false){
					Log::info("Logging break ");
					$similarPhotos = array();
					break;
				}

				if($i==0){
					$similarPhotos = $arrayPhotosId;
				}

			$similarPhotos = array_intersect($similarPhotos, $arrayPhotosId);
			$i++;

			}
			//To remove repeted values
			$similarPhotos = array_unique($similarPhotos);

			//To obtain name of similarPhotos
			foreach ($similarPhotos as $similarPhotosId ) {
				$photoObj = Photo::where('id',$similarPhotosId)->whereNull('deleted_at')->first();

 				if(!empty($photoObj) && !is_null($photoObj)){
 					$similarPhotosDB = DB::table('photos')
 					->select('id', 'name')
 					->where('id',$photoObj->id)
 					->get();
 					array_push($arrayPhotosDB,$similarPhotosDB[0]);
 				}

			}
		}


			return $arrayPhotosDB;
	}

	public static function licensePhoto($photo){
		$license = array();
		if($photo->allowCommercialUses == 'YES'){
			$textAllowCommercial = 'Permite o uso comercial da imagem ';
			if($photo->allowModifications == 'YES'){
				 $license[0] ='by';
				 $license[1] = $textAllowCommercial.'e permite modificações na imagem.';
			}elseif ($photo->allowModifications == 'NO') {
				 $license[0] ='by-nd';
				 $license[1] = $textAllowCommercial.'mas NÃO permite modificações na imagem.';
			}else {
				 $license[0] = 'by-sa';
				 $license[1] = $textAllowCommercial.'e permite modificações contato que os outros compartilhem de forma semelhante.';
			}
		}else{
			$textAllowCommercial = 'NÃO permite o uso comercial da imagem ';
			if($photo->allowModifications == 'YES'){
				$license[0] ='by-nc';
				$license[1] =$textAllowCommercial.'mas permite modificações na imagem.';
			}elseif ($photo->allowModifications == 'NO') {
				$license[0] = 'by-nc-nd';
				$license[1] = $textAllowCommercial.'e NÃO permite modificações na imagem.';
			}else {
				$license[0] = 'by-nc-sa';
				$license[1] = $textAllowCommercial.'mas permite modificações contato que os outros compartilhem de forma semelhante.';
			}
		}

		return $license;

	}

	public function scopeWithoutInstitutions($query) {
		return $query->whereNull('institution_id');
	}

	public function scopeWithInstitution($query, $institution) {
		$id = $institution instanceof Institution ? $institution->id : $institution;
		return $query->where('institution_id', $id);
	}

	public function scopeWithUser($query, $user) {
		$id = $user instanceof User ? $user->id : $user;
		return $query->where('user_id', $id);
	}

	public function scopeExcept($query, $photos) {
		if ($photos instanceof Photo) {
			return $query->where('id', '!=', $photos->id);
		}
		//instance of Eloquent\Collection
		return $query->whereNotIn('id', $photos->modelKeys());
	}

	public function scopeNotInAlbum($query, $album, $q = null) {
		return $query->whereDoesntHave('albums', function($query) use($album) {
			$query->where('album_id', $album->id);
		})->whereMatches($q);
	}

	public function scopeInAlbum($query, $album, $q = null) {
		return $query->whereHas('albums', function($query) use($album) {
			$query->where('album_id', $album->id);
		})->whereMatches($q);
	}

	public function scopeWhereMatches($query, $needle) {
		if ( empty($needle) ) {
			return $query;
		}
		return $query->where( function($q) use($needle) {
			$q->withTag($needle)->orWhere( function ($q) use($needle) {
				$q->withAttributes($needle);
			});
		});
	}

	public function scopeWithTag($query, $needle) {
		return $query->whereHas('tags', function($q) use($needle) {
			$q->where('name', 'LIKE', '%' . $needle . '%');
		});
	}

	public function scopeWithAttributes($query, $needle) {
		return $query->where('name', 'LIKE', '%'. $needle .'%')
			->orWhere('description', 'LIKE', '%'. $needle .'%')
			->orWhere('imageAuthor', 'LIKE', '%' . $needle . '%')
			->orWhere('country', 'LIKE', '%'. $needle .'%')
			->orWhere('state', 'LIKE', '%'. $needle .'%')
			->orWhere('city', 'LIKE', '%'. $needle .'%');
	}

	public function scopeWithBinomials($query, $binomials) {
		foreach($binomials as $binomial => $avg) {
			$query->whereIn('photos.id', function ($sub_query) use ($binomial, $avg) {
				$sub_query->select('photo_id')->from('binomial_evaluation')
					->whereRaw('binomial_id = ' . $binomial)
					->groupBy('photo_id')
					->havingRaw('avg(evaluationPosition) >= ' . ($avg - 5))
					->havingRaw('avg(evaluationPosition) <= ' . ($avg + 5));
			});
		}
		return $query;
	}

	public function scopeWithTags($query, $tags) {
		if ( ! empty($tags) ) {
			$query->whereHas('tags', function($sub_query) use ($tags) {
				$sub_query->whereIn('name', $tags);
			});
		}
		return $query;
	}

	public function scopeWithTagsVarious($query, $tags) {
		if(!empty($tags)) {

				$query->join('tag_assignments','tag_assignments.photo_id','=','photos.id');
				$query->join('tags','tags.id','=','tag_assignments.tag_id');
				$query->where(function($sub_query) use ($tags) {
					foreach ($tags as $tag) {
						$sub_query->orWhere('tags.name', '=', $tag);
					}
				});
		}
		return $query;
	}

	public function scopeWithAuthors($query, $authors) {
		if ( ! empty($authors) ) {
			$query->whereHas('authors', function($sub_query) use ($authors) {
				$sub_query->whereIn('name', $authors);


			});
		}
		return $query;
	}
	public function scopeWithAuthorsVarious($query, $authors) {

			if(!empty($authors)) {

				$query->join('photo_author','photo_author.photo_id','=','photos.id');
				$query->join('authors','authors.id','=','photo_author.author_id');
				$query->where(function($sub_query) use ($authors) {
					foreach ($authors as $author) {
						$sub_query->orWhere('authors.name', 'LIKE', '%' .  $author. '%');
					}
				});
			}

		return $query;
	}

	public function getDataUploadAttribute($value) {
		return $this->date->formatDatePortugues($this->attributes['dataUpload']);
	}



	public function getTranslatedDataCriacaoAttribute($raw_date) {
		return $this->date->translate($this->attributes['dataCriacao']);
	}



	public function getFormatWorkdateAttribute($dateWork,$type) {
		return  $this->date->formatToWorkDate($dateWork,$type);
	}

	public function getFormatDataCriacaoAttribute($dataCriacao,$type) {
		return  $this->date->formatToDataCriacao($dataCriacao,$type);
	}

	public static function import($attributes, $basepath) {
		$tombo = $attributes['tombo'];
		list( $image, $image_extension ) = static::getImage( $basepath, $tombo );
		$image_extension = strtolower($image_extension);
		$attributes['nome_arquivo'] = $tombo . '.' . $image_extension;
		$photo = static::updateOrCreateByTombo( $tombo, $attributes );
		$photo->saveImages( $image, $image_extension );
		$photo->saveMetadata($image_extension);
		return $photo;
	}

	public static function getImage($basepath, $tombo) {
		$image = ImageManager::find( $basepath . '/' . $tombo . '.*' );
		$image_extension = ImageManager::getOriginalImageExtension( $image );
		return array( $image, $image_extension );
	}

	public static function updateOrCreateByTombo($tombo, $newValues) {
		return static::updateOrCreateWithTrashed( array( 'tombo' => $tombo ), $newValues );
	}

	public static function updateOrCreateWithTrashed($attributes, $newValues) {
		$photo = static::withTrashed()->where( $attributes )->first();
		$photo = $photo ?: new static;
		$photo->fill( $newValues );
		if ( $photo->trashed() ) {
			$photo->restore();
		} else {
			if ( ! $photo->exists ) {
				$photo->dataUpload = date('Y-m-d H:i:s');
			}
			$photo->save();
		}
		return $photo;
	}

	public function saveImages($image, $extension = 'jpg') {
		try {
			$prefix = public_path() . '/arquigrafia-images/' . $this->id;
			ImageManager::makeAll( $image, $prefix, $extension );
		} catch (Exception $e) {
			$this->delete();
			throw $e;
		}
	}

	public function syncTags(array $tags) {
		$get_ids = function( $tag ) {
			return $tag instanceof Tag ? $tag->id : $tag;
		};
		$tags = array_map($get_ids, $tags);
		$this->tags()->sync($tags);
	}

	public static function search($input, $tags, $binomials, $authorsArea) {

		if(Session::has('CurrPage') && Session::get('CurrPage')!= 1){
		   Session::forget('CurrPage');
		}else{
		   Session::put('CurrPage',1);
		}
		foreach (['workdate', 'dataCriacao', 'dataUpload'] as $date) {
			if ( isset($input[$date])
					&& DateTime::createFromFormat('Y', $input[$date]) == FALSE ) {
					$input[$date] = Date::formatedDate($input[$date]);
			}
		}
		$query = static::query();
		$query->select(DB::raw('photos.*'));
		foreach (['allowCommercialUses', 'allowModifications'] as $license) {
			if ( isset($input[$license]) ) {
				$query->where($license, array_pull($input, $license) );
			}
		}
		if(Input::has('workAuthor_area')){
			$input = array_except($input, 'workAuthor_area');
		}
		foreach ( $input as $column => $value) {
			$query->where('photos.'.$column, 'LIKE', '%' . $value . '%');
		}

		$query->withTagsVarious($tags);
		$query->withBinomials($binomials);
		$query->withAuthorsVarious($authorsArea);

		$query->groupBy('photos.id');
		$resultSet = $query->get();
    	return $resultSet;
	}




	public function authorTextFormat($authorName){

		if(strpos($authorName, ",")){
			$arrayAuthor = explode(',', $authorName);
			$first = true;
			$authorString = "";
			foreach ($arrayAuthor as $t ) {
				if($first == true){
					$authorString.= strtoupper($t).", ";
				}else{
					$arrayText = explode(' ', $t);
					foreach ($arrayText as $a ) {
						if(strlen($a) > 3){
							$authorString.= ucwords($a)." ";
						}else{
							$authorString.= $a." ";
						}

					}
				}
				$first = false;
			}

		}else{
			$authorString = ucwords($authorName);
		}
		return $authorString;
	}

	public function hasInstitution() {
		return ! is_null ($this->institution_id);
	}


	public function scopeWithTagsName($query, $tag) {
		if(!empty($tag)) {
				$query->join('tag_assignments','tag_assignments.photo_id','=','photos.id');
				$query->join('tags','tags.id','=','tag_assignments.tag_id');
				$query->where(function($sub_query) use ($tag) {
						$sub_query->where('tags.name', '=', $tag);
				});
		}
		return $query;
	}

	public function scopeWithAuthorName($query, $author) {
		if(!empty($author)) {
				$query->join('photo_author','photo_author.photo_id','=','photos.id');
				$query->join('authors','authors.id','=','photo_author.author_id');
				$query->where(function($sub_query) use ($author) {
						$sub_query->where('authors.name', '=', $author);
				});
		}
		return $query;
	}


	//busca Simples
	public function scopeWithAttributesBuilder($query, $needle) {
		$qq = $query->orWhere('photos.name', 'LIKE', '%'. $needle .'%')
			->orWhere('photos.description', 'LIKE', '%'. $needle .'%')
			->orWhere('photos.imageAuthor', 'LIKE', '%' . $needle . '%')
			->orWhere('photos.country', 'LIKE', '%'. $needle .'%')
			->orWhere('photos.state', 'LIKE', '%'. $needle .'%')
			->orWhere('photos.city', 'LIKE', '%'. $needle .'%');
		return $qq;
	}

	public function scopeWithBinomialsxxx($query, $binomials) {
		foreach($binomials as $binomial => $avg) {
			$query->whereIn('photos.id', function ($sub_query) use ($binomial, $avg) { //id //photos.id
				$sub_query->select('photo_id')->from('binomial_evaluation')
					->whereRaw('binomial_id = ' . $binomial)
					->groupBy('photo_id')
					->havingRaw('avg(evaluationPosition) >= ' . ($avg - 5))
					->havingRaw('avg(evaluationPosition) <= ' . ($avg + 5));
			});
		}
		return $query;
	}

	public static function search2($needle,$perPage = 24 ) {

		$query = static::query()->select(DB::raw('photos.*'))
		->withAttributesBuilder($needle)->withTagsName($needle)
		->withAuthorName($needle)
		->groupBy('photos.id')->paginate($perPage);

    	return $query;
	}

	public static function searchPhotosField($needle,$perPage = 24 ) {
		$query = static::query()->select(DB::raw('photos.*'))
		->withAttributesBuilder($needle)
		->orderBy('photos.id')
		->groupBy('photos.id')
		->paginate($perPage);
		//$resultSet = $query->get();
    	//return $resultSet;
    	return $query;
	}

	public static function searchPhotosWithTags($needle,$perPage = 24 ) {
		$query = static::query()->select(DB::raw('photos.*'))
		->withTagsName($needle)
		->orderBy('photos.id')
		->groupBy('photos.id')
		->paginate($perPage);
    	return $query;
	}

	public static function searchPhotosWithAuthor($needle,$perPage = 24 ) {
		$query = static::query()->select(DB::raw('photos.*'))
		->withAuthorName($needle)
		->orderBy('photos.id')
		->groupBy('photos.id')
		->paginate($perPage);

    	return $query;
	}




	public function scopePhotosVarious($query, $photos, $q = null) {
		if(!empty($photos)) {
				$query->where(function($sub_query) use ($photos) {
					foreach ($photos as $photo) {
						$sub_query->orwhere('photos.id', '=', $photo->id);
					} })->whereMatches($q);
		}
		return $query;
	}


	public static function paginatePhotosSearch($photos, $perPage = 36,$q = null) {
		if($photos!= null){
			$qq = static::PhotosVarious($photos, $q)->orderBy('photos.created_at', 'DESC')->paginate($perPage);
			return $qq;
		}else{
			return null;
		}
	}


	public static function paginateAllPhotosSearch($photos, $q = null, $perPage = 36) {
		return static::PhotosVarious($photos,$q)->orderBy('photos.created_at', 'DESC')->paginate($perPage);
	}

	public static function paginateAllPhotosSearchAdvance($photos, $q = null, $perPage = 36) {
		return static::PhotosVarious($photos,$q)->orderBy('photos.created_at', 'DESC')->paginate($perPage);

	}

	public static function paginatePhotosSearchAdvance($photos, $perPage = 36,$q = null) {
		if($photos!= null)
			return static::PhotosVarious($photos, $q)->orderBy('photos.created_at', 'DESC')->paginate($perPage);
		else
			return null;

	}

	public static function fileNamePhoto($photo,$ext)
	{
		if($photo!= null)
			Photo::where('id',$photo->id)->update(['nome_arquivo' => $photo->id.".".$ext ]);
	}

	public function checkValidationFields($field) {
		$validation = ['city', 'country', 'description', 'district', 'imageAuthor', 'state', 'street', 'name', 'authors', 'workDate'];
		if(!in_array($field, $validation))
			throw new Exception('Unexpected field type');

		// Defining main query
		$query = Suggestion::join('photo_attribute_types', 'suggestions.attribute_type', '=', 'photo_attribute_types.id')
		->where('suggestions.photo_id', $this->id)->where('photo_attribute_types.attribute_type', $field);

		// Creating the sub-queries that we're going to use
		$query1 = clone $query;
		$query2 = clone $query;
		$query3 = clone $query;

    // Defining field content
    if ($field == 'authors') {
      // The 'authors' field has objects inside. This is the reason we have to implode the name of the authors
      $authors = array_map(function($author) { return $author['name']; }, json_decode(json_encode($this[$field]),TRUE));
      $fieldContent = implode('; ', $authors);
    } else {
      $fieldContent = $this[$field];
    }

    // Returning the status
		if($query1->whereNull('suggestions.accepted')->count() > 0) {
      // If the accepted field on suggestion is null, return reviewing
			return 'reviewing';
    } elseif($query2->where('suggestions.text', $fieldContent)->where('suggestions.accepted', 1)->count() > 0)
    {
			return 'reviewed';
    } else {
			return 'none';
		}
	}

  /**
   * This function returns the content of a given fieldName
   * @params  {String}  fieldName  The name of the field that you wanna get the content
   * @return  {Array, String}  Returns a string or a array of strings of that given content
   */
  public function getFieldContent($fieldName) {
    // If the field is not authors, we can just return the field content
    if ($fieldName !== 'authors') {
      return $this[$fieldName];
    }
    // If the field required is 'authors' so we need to mount our data array
    return array_map(function ($item) {
      return $item->name;
    }, json_decode(json_encode($this->authors)));
  }

	public function checkPhotoReviewing(){
		$fields = ['city', 'country', 'description', 'district', 'imageAuthor','state', 'street', 'name', 'authors', 'workDate'];
		foreach ($fields as $field) {
			if($this->checkValidationFields($field) ==  'reviewing')
				return true;
		}
		return false;
	}

	public static function updateSuggestion($field, $data, $id){
		$photo = Photo::find($id);

		switch ($field) {
			case 'city':
				$photo->city = $data;
				break;
			case 'country':
				$photo->country = $data;
				break;
			case 'description':
				$photo->description = $data;
				break;
			case 'district':
				$photo->district = $data;
				break;
			case 'imageAuthor':
				$photo->imageAuthor = $data;
				break;
			case 'state':
				$photo->state = $data;
				break;
			case 'street':
				$photo->street = $data;
				break;
			case 'name':
				$photo->name = $data;
				break;
			case 'project_author':
				$photo->project_author = $data;
				break;
      case 'authors':
        $author = new Author();
        // First, we're going to remove the current authors in photo
        $author->deleteAuthorPhoto($photo);
        // Then, we're saving the new authors to photo
        $author->saveAuthors($data, $photo);
			case 'workDate':
				$photo->workDate = $data;
				break;
			default:
				# code...
				break;
		}
		$photo->save();
	}

}
