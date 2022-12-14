<?php

namespace App\Http\Controllers\Albums;

use App\Models\Institutions\Institution;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Albums\Album;
use App\Models\Albums\AlbumElements;
use App\Models\Photos\Photo;
use App\Models\Users\User;
use Auth;
use Session;
use Response;
use Cache;


class AlbumsController extends Controller {

	public function __construct() {
		// $this->beforeFilter('auth',
		// 	array( 'except' => 'show' ));
		// $this->beforeFilter('ajax',
		// 	array( 'only' => array(
		// 		'updateInfo',
		// 		'detachPhotos',
		// 		'attachPhotos',
		// 		'paginateAlbumPhotos',
		// 		'paginatePhotosNotInAlbum',
		// 		'paginateCoverPhotos',
		// 		'getList'
		// 	)));
	}

	public function index() {
		if(Session::has('institutionId')) {
			// $albums = Album::withInstitution(Session::get('institutionId'))->get();
			$albums = Album::with('Instituition')->where('institution_id', Session::get('institutionId'))->get();

		} else {

			// $albums = Album::withUser(Auth::user()->id)->withoutInstitutions()->get();
			$albums = Album::with('User')->where('user_id', Auth::user()->id)->get();

		}
		return view('albums.index')->with('albums', $albums);
	}

	public function create(Request $request) {
		$url = URL::to('/albums/photos/add');
		if ( Session::has('institutionId') ) {
			$photos = Photo::paginateInstitutionPhotos(Session::get('institutionId'));
		} else {
			$photos = Photo::paginateUserPhotos(Auth::user());
		}
		$image = Photo::find( $request->get('photo') );
		return view('albums.form')
			->with(['photos' => $photos,
				'url' => $url,
				'maxPage' => $photos->lastPage(),
				'page' => 1,
				'type' => 'add',
				'image' => $image,
			]);
	}

	public function show($id) {
		$album = Album::find($id);

		if (is_null($album)) {
			return redirect()->to('/home');
		}

		$user = $album->user;

		$photos = AlbumElements::raw((function($collection) use ($id) {
						return $collection->aggregate([
							[
							 '$lookup' => [
									'from' => 'photos',
									'localField' => 'photo_id',
									'foreignField'=> 'id',
									'as' => 'photo'
								],
						 ],
						 [
							 '$match' => [
								 'album_id' => $id,
								 'photo.id' => [
									 '$exists'=> true
								 ],
							 ]
						 ],
						[
							'$project' => [
								'photo_id' => 1,
								'photo.id' => 1,
								'photo.name' => 1,
							]
						],
						]);
				}));

		$other_albums = Album::where($id, '!=', 'id')->where('user_id', $user->id)->get();

		$elements = Album::raw((function($collection) use ($id) {
						return $collection->aggregate([
							[
							 '$lookup' => [
									'from' => 'album_elements',
									'localField' => 'id',
									'foreignField'=> 'album_id',
									'as' => 'elements'
								],
						 ],
						 [
							'$lookup' => [
								 'from' => 'photos',
								 'localField' => 'elements.photo_id',
								 'foreignField'=> 'id',
								 'as' => 'photos'
							 ],
						],
						// [
						// 	'$match' => [
						// 		'photos.id' => [
						// 			'$exists'=> true
						// 		],
						// 	]
						// ],
						]);
				}))->where('user_id', $user->id)->where($id, '!=', 'id');

				// $other_albums_photos = array_values($other_albums_photos->toArray());

				$other_albums_photos = [];
				//
				foreach ($elements as $key => $value) {
					$other_albums_photos += [$value->id => $value];
				}

		// return \Response::json($other_albums );

		return view('albums.show')
			->with([
				'photos' => $photos,
				'album' => $album,
				'user' => $user,
				'other_albums' => $other_albums,
				'other_albums_photos' => json_encode($other_albums_photos),
				// 'institutionlogged'=> $institutionlogged
			]);
	}

	public function store(Request $request) {
		$photos = (array) $request->get('photos_add');
		$cover = Photo::find((empty($photos) ? null : array_values($photos)[0]));
		$user = Auth::user();

		if( Session::has('institutionId') ) {
			$institution = Institution::find(Session::get('institutionId'));
		} else {
			$institution = NULL;
		}
		$album = Album::create([
			'title' => $request->get('title'),
			'description' => $request->get('description'),
			'user' => $user,
			'cover' => $cover,
			'institution' => $institution
		]);
		if ( $album ) {
			if ( !empty($photos) ) {
				$album->attachPhotos($photos);
			}
			return redirect()->to('/albums/' . $album->id);
		}
		return redirect()->to('/albums/create')->withErrors($album->getErrors());
	}

	public function delete($id) {
		$album = Album::find($id);
		$user = Auth::user();
		$institution = Institution::find( Session::get('institutionId') );
		if ( isset($album) && ( $user->equal($album->user) ||
			(isset($institution) && $institution->equal($album->institution) ) ) )
		{
			$album->delete();
			Session::put('album.delete', '??lbum ' . $album->title . ' deletado com sucesso.');
		}
		return redirect()->to('albums');
	}

	public function edit($id) {
		$user = Auth::user();
		$album = Album::find($id);
		$institution = Institution::find( Session::get('institutionId') );
		if ( Session::has('institutionId')) {
			if(is_null($album) || !$institution->is($album->institution))
				return redirect()->to('/home');
		} else if(is_null($album) || !($user->is($album->user) && $album->institution_id == null)){
				return redirect()->to('/home');
		}

		$album_photos = Photo::paginateAlbumPhotos($album);
		if ( isset($institution) ) {
			$other_photos = Photo::paginateInstitutionPhotosNotInAlbum($institution, $album);
		} else {
			$other_photos = Photo::paginateUserPhotosNotInAlbum($user, $album);
		}
		$other_photos_count = $other_photos->total();
		$maxPage = $other_photos->lastPage();
		$rmMaxPage = $album_photos->lastPage();
		$url = URL::to('/albums/' . $album->id . '/paginate/other/photos/');
		$rmUrl = URL::to('/albums/' . $album->id . '/paginate/photos');
		return view('albums.edition')
			->with(
				['album' => $album,
				'album_photos' => $album_photos,
				'other_photos' => $other_photos,
				'other_photos_count' => $other_photos_count,
				'page' => 1,
				'maxPage' => $maxPage,
				'rmMaxPage' => $rmMaxPage,
				'url' => $url,
				'rmUrl' => $rmUrl,
				'type' => 'rm',
				'photos' => null] );
	}

	public function removePhotos($id) {
		$album = Album::find($id);
		$photos = $request->except('_token');
		$album->detach($photos);
		return redirect()->to('albums/' . $id);
	}

	public function insertPhotos($id) {
		$album = Album::find($id);
		$photos = $request->except('_token');
		$album->attach($photos);
		return redirect()->to('albums/' . $id);
	}

	public function updateInfo(Request $request, $id) {
		$album = Album::find($id);
		if ( is_null($album) ) { return redirect()->to('/home');	}
		$title = $request->get('title');
		$description = $request->get('description');
		$cover = Photo::find( $request->get('cover') );
		$album->updateInfo( $title, $description, $cover );
		if ( $album->save() ) {
			return Response::json("success");
		}
		return Response::json($album->getErrors());
	}

	public function paginateByUser() {
		if ( Session::has('institutionId') ) {
			$photos = Photo::paginateInstitutionPhotos(Session::get('institutionId'));
		} else {
			$photos = Photo::paginateUserPhotos(Auth::user());
		}
		$page = $photos->getCurrentPage();
		return Response::json(view('albums.includes.album-photos')
			->with(['photos' => $photos, 'page' => $page, 'type' => 'add'])
			->render());
	}

	public function paginateByAlbum($id) {
		$album = Album::find($id);
		$photos = Photo::paginateAlbumPhotos($album);
		$page = $photos->getCurrentPage();
		return Response::json(view('albums.includes.album-photos')
			->with(['photos' => $photos, 'page' => $page, 'type' => 'rm'])
			->render());
	}

	public function paginateByOtherPhotos($id) {
		dd("chegou");
		$album = Album::find($id);
		$photos = Photo::paginateOtherPhotos(Auth::user(), $album->photos);
		$page = $photos->getCurrentPage();
		return Response::json(view('albums.includes.album-photos')
			->with(['photos' => $photos, 'page' => $page, 'type' => 'add'])
			->render());
	}

	public function paginateCoverPhotos($id) {
		$album = Album::find($id);
		$photos = Photo::paginateAlbumPhotos($album, 48);
		$photos_ids = [];
		foreach ($photos as $photo) {
			$photos_ids[] = $photo->id;
		}
		return $photos_ids;
	}

	public function getList($id) {
		//
		$albums_with_photo = Photo::find($id)->albums; // albums que j?? t??m essa foto

		if(Session::has('institutionId')) {
			$albums = Album::withInstitution(Session::get('institutionId'))->except($albums_with_photo)->get();
		}else{
			$albums = Album::with('User')->where('user_id', Auth::user()->id)->whereNull('institution_id')->get();
			// $albums = Album::withUser( Auth::user() )->whereNull('institution_id')->except($albums_with_photo)->get();
		}

		return Response::json(view('albums.get-albums')
			->with(['albums' => $albums, 'photo_id' => $id])
			->render());
	}

	public function addPhotoToAlbums(Request $request) {
		$albums_id = $request->get('albums');
		$photo = Photo::find($request->get('_photo'));
		$albums = Album::findMany($albums_id);

		foreach ($albums as $album)
		{
			$album->photos()->sync(array($photo->id), false);
			if ( !isset($album->cover) ) {
				$album->cover()->associate($photo);
			}
		}
		if ($request->has('create_album')) {
			if ($albums->isEmpty()) {
				return redirect()->to('/albums/create')->with('image', $photo);
			} else {
				return redirect()->to('/albums/create')->with([
					'message' => '<strong>Imagem adicionada com sucesso ao(s) seu(s) ??lbum(ns)</strong>',
					'image' => $photo
				]);
			}
		}
		if ($albums->isEmpty()) {
			return redirect()->to('/photos/' . $photo->id);
		} else {
			return redirect()->to('/albums')->with('message', '<strong>Imagem adicionada com sucesso ao(s) seu(s) ??lbum(ns)</strong>');
		}
	}

	public function destroy($id) {
		$album = Album::find($id);
		$album->delete();
		return redirect()->to('/albums');
	}

	public function removePhotoFromAlbum($album_id, $photo_id) {
		$album = Album::find($album_id);
		$album->detachPhotos($photo_id);
		return redirect()->to('/albums/' . $album->id);
	}

	public function detachPhotos($id) {
		try {
			$album = Album::find($id);
			$photos = $request->get('photos');
			$album->detachPhotos($photos);
		} catch (Exception $e) {
			return Response::json('failed');
		}
		return $this->paginateAlbumPhotos($id);
	}

	public function attachPhotos(Request $request, $id) {
		try {
			$album = Album::find($id);
			$photos = $request->get('photos');
			$album->attachPhotos($photos);
		} catch (Exception $e) {
			return Response::json('failed');
		}
		return $this->paginatePhotosNotInAlbum($request, $id);
	}

	public function paginateAlbumPhotos(Request $request, $id) {
		$album = Album::find($id);
		$query = $request->has('q') ? $request->get('q') : '';
		$pagination = Photo::paginateFromAlbumWithQuery($album, $query);
		return $this->paginationResponse($pagination, 'rm');
	}

	public function paginatePhotosNotInAlbum(Request $request=null, $id) {
		$album = Album::find($id);
		$user = Auth::user();

		$inst = Institution::find(Session::get('institutionId'));
		if ( is_null($album) || ! ( $user->is($album->user) ||
			(isset($institution) && $institution->is($album->institution) ) ) ) {
			return Response::json('failed');
		}
		$query = $request->has('q') ? $request->get('q') : '';
		$which_photos = $request->get('wp');
		$pagination = null;
		if ( $which_photos == 'user' ) {
			if ( isset($inst) ) {
				$pagination = Photo::paginateInstitutionPhotosNotInAlbum($inst, $album, $query);
			} else {
				$pagination = Photo::paginateUserPhotosNotInAlbum($user, $album, $query);
			}
		} else {
			$pagination = Photo::paginateAllPhotosNotInAlbum($album, $query);
		}
		return $this->paginationResponse($pagination, 'add');
	}

	private function paginationResponse($photos, $type) {
		$count = $photos->total();
		$page = $photos->currentPage();
		$response = [];
		$response['content'] = view('albums.includes.album-photos-edit')
			->with(['photos' => $photos, 'page' => $page, 'type' => $type])
			->render();
		$response['maxPage'] = $photos->lastPage();
		$response['empty'] = ($photos->count() == 0);
		$response['count'] = $count;
		return Response::json($response);
	}

}
