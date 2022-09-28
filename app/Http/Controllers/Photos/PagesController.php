<?php
/*
|--------------------------------------------------------------------------
| Default Page Controller
|--------------------------------------------------------------------------
 */
namespace App\Http\Controllers\Photos;

use App\lib\log\EventLogger;
use App\lib\date\Date;
use App\Models\Institutions\Institution;
use App\Models\Collaborative\Comment;
use App\Models\Collaborative\Like;
use App\Models\Evaluations\Binomial;
use App\Models\Evaluations\Evaluation;
use App\Models\Collaborative\Tag;
use App\Http\Controllers\Controller;
use App\Models\Photos\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Users\User;
use App\Models\Photos\Author;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use DateTime;
use Session;
use Auth;

class PagesController extends Controller {
  protected $date;

  public function __construct(Date $date = null) {
    $this->date = $date ?: new Date;
  }

  public function main() {
    return view('landing');
  }

  public function landing($language=null) {
    if($language == 'en'){
      App::setLocale('en');
    } else {
      App::setLocale('pt');
    }
    return view('landing');
  }

  public function home() {
    if(Session::has('last_search'))
      Session::forget('last_search');

    if(Session::has('last_advanced_search'))
      Session::forget('last_advanced_search');

    $photos = Photo::all()->random(150);

    if(Session::has('institutionId')) {
      $institution = Institution::find(Session::get('institutionId'));
    } else {
      $institution = null;
    }

    EventLogger::printEventLogs(null, "home", null, "Web");

    return view('index', ['photos' => $photos, 'institution' => $institution ]);
  }

  public function panel() {
    $photos = Photo::orderByRaw("RAND()")->take(150)->get();
    return view('api.panel', ['photos' => $photos]);
  }

  public static function userPhotosSearch($needle) {
    $query = User::where('id', '>', 0);
    $query->where('name', 'LIKE', '%'. $needle .'%');
    $userList = $query->get();
    return $userList->pluck('id');
  }

  private static function streetAndCitySearch(&$needle,&$txtcity) {
    $allowed = "/[^a-z\\.\/\sçáéíóúãàõ]/i";
    $txtstreet=  preg_replace($allowed,"",$needle);
    $txtstreet = rtrim($txtstreet);
    $needle = $txtstreet;

    $query = Photo::orderByRaw("RAND()");
    $query->where('city', 'LIKE', '%' . $txtcity . '%');
    $query->where('street', 'LIKE', '%' . $txtstreet . '%');
    $query->whereNull('deleted_at');
    $photos = $query->get();
    return $photos;
  }

  private static function dateSearch(&$needle,&$type) {
    if($type=='work'){
      Log::info("Logging information of work date<".$needle.">");
      $dateType = 'workdate';

    }elseif ($type=='img') {
      Log::info("Logging information of image date<".$needle.">");
      $dateType = 'dataCriacao';

    }elseif ($type=='up') {
      Log::info("Logging information for upload <".$needle.">");
      $dateType = 'dataUpload';
      $date = new DateTime($needle);
      $needle =  $date->format('Y-m-d');
      Log::info("Logging information for format upload <".$needle.">");
    }
    $query = Photo::orderByRaw("RAND()");
    $query->where($dateType, 'LIKE', '%' . $needle . '%');
    $query->whereNull('deleted_at');
    $photos = $query->get();
    return $photos;
  }

  public static function yearSearch(&$needle,&$dateFilter,&$date) {
    $dateFilter = [
      'di'=>'Data da Imagem',
      'du'=>'Data de Upload',
      'do'=>'Data da Obra'
    ];


    if(!empty($date)) {
      if($date == 'di' ) $dateType = 'dataCriacao';
      if ($date == 'du' ) $dateType = 'dataUpload';
      if ($date == 'do' ) {$dateType = 'workdate'; }

      $query = Photo::orderByRaw("RAND()");
      $query->where($dateType, 'LIKE', '%' . $needle . '%');
      $query->whereNull('deleted_at');
      $photos = $query->get();
      return $photos;
    } else {
      $query = Photo::orderByRaw("RAND()");
      $query->where('dataCriacao', 'LIKE', '%' . $needle . '%');
      $query->orWhere('dataUpload', 'LIKE', '%' . $needle . '%');
      $query->orWhere('workdate', 'LIKE', '%' . $needle . '%');
      $query->whereNull('deleted_at');
      $photos = $query->get();
      return $photos;
    }
  }

  public function searchBinomial($binomial_id, $option, $value = null) {
    $bin = Binomial::find($binomial_id);
    $bi_opt = $option == 1 ? $bin->firstOption : $bin->secondOption;
    $photos = Evaluation::getPhotosByBinomial($bin, $option, $value);
    $value = $option == 1 ? 100 - $value : $value;
    return view('/search',
      [
        'tags' => [], 'photos' => $photos, 'query' => '',
        'city' => '', 'dateFilter' => [], 'binomial_option' => $bi_opt,
        'value' => $value
      ]);
  }

  public function search(Request $request) {
    if ( $request->has('bin') ) {
      dd("oi");

      return $this->searchBinomial(
        $request->get('bin'),$request->get('opt'), $request->get('val')
      );
    }

    $pageVisited = 0;
    $needle = trim($request->get("q"));
    $txtcity = $request->get("city");
    $type = $request->get("t");
    $dateFilter = null;
    $date = $request->get("d");
    $authorFilter = null;

    $url= null;
    $maxPage = 0;
    $photosTotal = 0;
    $photosPages = null;
    $photosAll = 0;
    $pageRetrieved = 1;
    $haveSession = 0;
    $pageLinked = 0;

    if ( $request->has('type') ) {
      $authorFilter= $request->get('type');
    }

    if ($needle != "") {
      if (!$request->has('pg') ){
        Session::forget('CurrPage');
      }
      $tags = null;
      $allAuthors =  null;
      $query = Tag::where('name', 'LIKE', '%' . $needle . '%')->where('count', '>', 0);
      $tags = $query->get();

      $allAuthors = DB::table('authors')
        ->join('photo_author', function($join) use ($needle)
        { $join->on('authors.id', '=', 'photo_author.author_id')
        ->where('name', 'LIKE', '%' . $needle . '%');
        })->groupBy('authors.id')->get();

      if ($txtcity != "") {
        $photos = static::streetAndCitySearch($needle,$txtcity);
      } elseif ((DateTime::createFromFormat('Y-m-d', $needle) !== FALSE || DateTime::createFromFormat('Y-m-d H:i:s', $needle) !== FALSE )&& !empty($type)) {
        $photos = static::dateSearch($needle,$type);
      } elseif (DateTime::createFromFormat('Y', $needle) !== FALSE) {
        $photos = static::yearSearch($needle,$dateFilter,$date);
      } else {
        $idUserList = static::userPhotosSearch($needle);

        $query = Photo::where(function($query) use($needle, $idUserList) {
          $query->where('name', 'LIKE', '%'. $needle .'%');
          $query->orWhere('description', 'LIKE', '%'. $needle .'%');
          $query->orWhere('imageAuthor', 'LIKE', '%' . $needle . '%');
          $query->orWhere('country', 'LIKE', '%'. $needle .'%');
          $query->orWhere('state', 'LIKE', '%'. $needle .'%');
          $query->orWhere('city', 'LIKE', '%'. $needle .'%');
          if ($idUserList != null && !empty($idUserList)) {
            $query->orWhereIn('user_id', $idUserList);}
        });
        $photos =  $query->orderBy('created_at', 'DESC')->get();
      }


      // se houver uma tag exatamente como a busca, pegar todas as fotos dessa tag e juntar no painel
      $query = Tag::where('name', '=', $needle);
      $tags = $query->get();
      foreach ($tags as $tag) {
        $byTag = $tag->photos;
        $photos = $photos->merge($byTag);
      }

      if($authorFilter != null) {
        $query = Author::where('name', '=', $needle);
        $author = $query->get();
        if($author->first()) {
          $byAuthor = $author->first()->photos;
          $photos = $photos->merge($byAuthor);
        }
      } else {
        $queryAuthor = Author::where('name', 'LIKE', '%' . $needle . '%');
        $authors = $queryAuthor->get();
        foreach ($authors as $author) {
          $byAuthor = $author->photos;
          $photos = $photos->merge($byAuthor);
        }
      }

      $query = Institution::where(function($query) use($needle) {
        $query->where('name', 'LIKE', '%'. $needle .'%');
        $query->orWhere('acronym', '=',  $needle);
      });
      $institutions = $query->get();

      foreach ($institutions as $institution) {
        $byInstitution = $institution->photos;
        $photos = $photos->merge($byInstitution);
      }

      $photosAll = $photos->count();

      // Searching users
      $query = User::where(function($query) use($needle) {
        $query->where('name', 'LIKE', '%'. $needle .'%')->where('active', '=', 'yes');
      });
      $users = $query->get();

      if (Auth::check()) {
        $user_id = Auth::user()->id;
        $user_or_visitor = "user";
      } else {
        $user_or_visitor = "visitor";
        session_start();
        $user_id = session_id();
      }

      EventLogger::printEventLogs(NULL, 'search', ['search_query' => $needle, 'search_size' => str_word_count($needle)],'Web');

      if(Session::has('CurrPage') && Session::get('CurrPage')!= 1) {
        $pageRetrieved = Session::get('CurrPage');
        $haveSession = 1;
      }

      if($photos->count() != 0) {
        $photosPages = Photo::paginatePhotosSearch($photos);
        $photosTotal = $photosPages->total();
        $maxPage = $photosPages->lastPage();

        $url = URL::to('/search'. '/paginate/other/photos/');

        Session::put('last_search',
          ['tags' => $tags, 'photos' => $photos, 'query'=>$needle, 'city'=>$txtcity,
          'dateFilter'=>$dateFilter, 'authors' => $allAuthors,
          'url' => $url,'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
          'photosAll' => $photosAll, 'pageVisited'=> $pageVisited, 'typeSearch'=> 'simples' ]);

        if($request->has('pg') && $haveSession != 0 ) {
          $pageVisited = 1;
          Session::forget('CurrPage');
        }
      } else {
        Session::forget('last_search');
        Session::forget('CurrPage');
        Session::forget('paginationSession');
      }

      return view('/search',['tags' => $tags, 'photos' => $photosPages,
        'query'=>$needle, 'city'=>$txtcity,'dateFilter'=>$dateFilter,
        'authors' => $allAuthors ,'needle' => $needle,'url' => $url,
        'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
        'photosAll' => $photosAll,'pageVisited'=> $pageVisited, 'users' => $users ]);
    }else {
      if(Session::has('last_search') && $request->has('pg')) {
        return view('/search', Session::get('last_search'));
      }else{// busca vazia
        return view('/search',['tags' => [], 'photos' => [], 'query' => "", 'city'=>"",
          'dateFilter'=>[], 'authors' =>[],
          'url'=>null,'photosTotal'=> 1,'maxPage' => 1, 'page' => 1, 'photosAll' => 0 , 'pageVisited'=> $pageVisited ]);
      }
    }

  }

  private static function searchTags($t) {
    $query = Tag::where('name','=', $t);
    $tagList = $query->get();
    return $tagList->pluck('id');
  }

  public function advancedSearch(Request $request) {
    $pageVisited = 0;
    $fields = $request->only( array(
      'name', 'description', 'city', 'state', 'country',
      'imageAuthor', 'dataCriacao', 'dataUpload', 'workdate', 'district',
      'street', 'allowCommercialUses', 'allowModifications', 'institution_id',
      'tombo'
    ));
    $fields = array_filter(array_map('trim', $fields));

    $url= null;
    $maxPage = 0;
    $photosTotal = 0;
    $photosPages = null;
    $pageRetrieved = 1;
    $haveSession = 0;
    $pageLinked = 0;

    if (Session::has('CurrPage') && Session::get('CurrPage')!= 1) {
      $pageRetrieved = Session::get('CurrPage');
      $haveSession = 1;
    } else {
      Session::put('CurrPage',1);
    }

    $institutions = Institution::institutionsList();

    if (count($fields) == 0) { // busca vazia
      if(Session::has('last_advanced_search')){
        return view('/advanced-search', Session::get('last_advanced_search'));

      }else {  // busca vazia

        return view('/advanced-search',
          ['tags' => [], 'photos' => [], 'query' => "", 'binomials' => Binomial::all(),'authorsArea' => [],
          'url'=> null,'photosTotal'=> 1,'maxPage' => 1, 'page' => $pageRetrieved,
          'photosAll' => 0 , 'pageVisited'=> $pageVisited,'typeSearch'=> 'advance', 'institutions' => $institutions  ]);
      }
    }
    $binomials = array();
    if ( $request->has('binomial_check') ) {
      foreach (Binomial::all() as $binomial) {
        if ( $request->has('value-' . $binomial->id) ) {
          $binomials[$binomial->id] = $request->get('value-' . $binomial->id);
        }
      }
    }

    //Adding search by tags
    $tags = str_replace(array('\'', '"', '[', ']'), '', $request->get('tags'));
    $tags = Tag::transform($tags);


    $authorsArea = str_replace(array('","'), '";"', $request->get('workAuthor_area'));
    $authorsArea = str_replace(array('\'', '"', '[', ']'), '', $authorsArea);
    $authorsArea = Author::transform($authorsArea);

    $photos = Photo::search($request, Arr::except($fields, 'tags'), $tags, $binomials,$authorsArea);


    if( ($count_photos = $photos->count()) == 0 ) {
      $message = 'A busca não retornou resultados.';
    }elseif ( $count_photos == 1 ) {
      $message = 'Verifique abaixo a ' . $count_photos . ' imagem encontrada.';
    }else {
      $message = 'Verifique abaixo as ' . $count_photos . ' imagens encontradas.';
    }
    $tags = $tags == '' ? [] : $tags;


    $photos = $photos->count() == 0 ? [] : $photos;

    if($request->has('pg') && $haveSession != 0 ) {
      $pageVisited = 1;
      Session::forget('CurrPage');
    }

    $photosPages = Photo::paginatePhotosSearchAdvance($photos);
    if($photosPages != null) {
      $photosTotal = $photosPages->total();
      $maxPage = $photosPages->lastPage();
    }
    $url = URL::to('/search/more'. '/paginate/other/photos/');

    Session::put('last_advanced_search', ['tags' => $tags, 'photos' => $photos,
      'binomials' => Binomial::all(), 'authorsArea' => $authorsArea, 'message' => $message,
      'url' => $url,'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
      'pageVisited'=> $pageVisited, 'typeSearch'=> 'advance', 'institutions' => $institutions
    ]);

    if(Session::has('last_advanced_search')){
      $lastSearchAdvance = Session::get('last_advanced_search');
      $typeSearch = $lastSearchAdvance['typeSearch'];
      $currentPage = $lastSearchAdvance['page'];
    }


    return view('/advanced-search',
      ['tags' => $tags, 'photos' => $photosPages, //'photos' => $photos,
      'binomials' => Binomial::all(), 'authorsArea' => $authorsArea, 'message' => $message,
      'url' => $url,'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
      'pageVisited'=> $pageVisited,'typeSearch'=> 'advance', 'institutions' => $institutions ]);
  }

  public function paginatePhotosResult(Request $request) {
    if(Session::has('last_search')){
      Log::info("si session fotos");
      $lastSearch = Session::get('last_search');
      $photos = $lastSearch['photos'];
    }
    $query = $request->has('q') ? $request->get('q') : '';

    $pagination = Photo::paginateAllPhotosSearch($photos,$query);
    return $this->paginationResponseSearch($pagination, 'add');
  }

  public function paginatePhotosResultAdvance() {
    if(Session::has('last_advanced_search')){
      $lastSearchAdvance = Session::get('last_advanced_search');
      $photos = $lastSearchAdvance['photos'];
    }

    $query = $request->has('q') ? $request->get('q') : '';

    $pagination = Photo::paginateAllPhotosSearchAdvance($photos,$query);
    return $this->paginationResponseSearch($pagination, 'add');
  }

  private function paginationResponseSearch($photos, $type) {
    Log::info("paginateRsp");
    $count = $photos->total();
    $perPage = $photos->getPerPage();
    $page = $photos->getCurrentPage();

    if(Session::has('CurrPage')){
      Log::info("pageGetWithSession".$page);
    }

    $fromPage = $photos->getFrom();

    $toPage = $photos->getTo();

    Log::info("Response>> CurrPage=".$page." FromPage=".$fromPage." ToPage=".$toPage." count=".$count." perPage=".$perPage);

    Session::put('FromPage', $fromPage);
    Session::put('ToPage', $toPage);
    Session::put('CurrPage', $page);

    $response = [];
    $response['content'] = view('photos.includes.searchResult_include')
      ->with(['photos' => $photos, 'page' => $page, 'type' => $type])
      ->render();
    $response['maxPage'] = $photos->lastPage();
    $response['empty'] = ($photos->count() == 0);
    $response['count'] = $count;

    return Response::json($response);
  }
}
