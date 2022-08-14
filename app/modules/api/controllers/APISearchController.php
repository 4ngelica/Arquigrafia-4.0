<?php
namespace modules\api\controllers;
use lib\log\EventLogger;
use modules\collaborative\models\Tag;
use modules\institutions\models\Institution;

class APISearchController extends \BaseController {

	public function search() { 
        $needle = trim(\Input::get("q"));
        $user_id = \Input::get("user_id");

        if ($needle != "") {        
            $tags = null;
            $allAuthors =  null;
            $query = Tag::where('name', 'LIKE', '%' . $needle . '%')->where('count', '>', 0);  
            $tags = $query->get(); 
            
            $allAuthors = \DB::table('authors')
            ->join('photo_author', function($join) use ($needle)
            { $join->on('authors.id', '=', 'photo_author.author_id')
              ->where('name', 'LIKE', '%' . $needle . '%');
            })->groupBy('authors.id')->get();
                                          
            $idUserList = \PagesController::userPhotosSearch($needle);
			$query = \Photo::where(function($query) use($needle, $idUserList) {
                $query->where('name', 'LIKE', '%'. $needle .'%');  
                $query->orWhere('description', 'LIKE', '%'. $needle .'%');  
                $query->orWhere('imageAuthor', 'LIKE', '%' . $needle . '%');
                $query->orWhere('country', 'LIKE', '%'. $needle .'%');  
                $query->orWhere('state', 'LIKE', '%'. $needle .'%'); 
                $query->orWhere('city', 'LIKE', '%'. $needle .'%'); 
                if ($idUserList != null && !empty($idUserList)) {
                    $query->orWhereIn('user_id', $idUserList);
                }
            });
            $photos =  $query->orderBy('created_at', 'DESC')->get();

            $query = Tag::where('name', '=', $needle); 
            $tags = $query->get();
            foreach ($tags as $tag) { 
                $byTag = $tag->photos;                
                $photos = $photos->merge($byTag);
            }   

            $queryAuthor = \Author::where('name', 'LIKE', '%' . $needle . '%'); 
            $authors = $queryAuthor->get();
            foreach ($authors as $author) { 
                $byAuthor = $author->photos;                
                $photos = $photos->merge($byAuthor);                
            }

            $query = Institution::where(function($query) use($needle) {
                    $query->where('name', 'LIKE', '%'. $needle .'%');
                    $query->orWhere('acronym', '=',  $needle);
                });
            $institutions =  $query->get(); 
            
            foreach ($institutions as $institution) { 
                $byInstitution = $institution->photos;
                $photos = $photos->merge($byInstitution);
            }  
        }
        if (isset($photos)) {
            if (!is_null($photos)) {
                $photos = $photos->sortByDesc('id')->take(20);

                /* Registro de logs */
                $eventContent['search_query'] = $needle;
                $eventContent['search_size'] = str_word_count($needle);
                $eventContent['user'] = $user_id;
                EventLogger::printEventLogs(NULL, 'search', $eventContent,'mobile');

                return \Response::json($photos);
            }
        }

        return \Response::json("");
	}

	public function moreSearch() {
        $needle = trim(\Input::get("q"));
        $max_id = \Input::get("max_id");

        if ($needle != "") {        
            $tags = null;
            $allAuthors =  null;
            $query = Tag::where('name', 'LIKE', '%' . $needle . '%')->where('count', '>', 0);  
            $tags = $query->get(); 
            
            $allAuthors = \DB::table('authors')
            ->join('photo_author', function($join) use ($needle)
            { $join->on('authors.id', '=', 'photo_author.author_id')
              ->where('name', 'LIKE', '%' . $needle . '%');
            })->groupBy('authors.id')->get();
                                          
            $idUserList = \PagesController::userPhotosSearch($needle);
            $query = \Photo::where(function($query) use($needle, $idUserList) {
                $query->where('name', 'LIKE', '%'. $needle .'%');  
                $query->orWhere('description', 'LIKE', '%'. $needle .'%');  
                $query->orWhere('imageAuthor', 'LIKE', '%' . $needle . '%');
                $query->orWhere('country', 'LIKE', '%'. $needle .'%');  
                $query->orWhere('state', 'LIKE', '%'. $needle .'%'); 
                $query->orWhere('city', 'LIKE', '%'. $needle .'%'); 
                if ($idUserList != null && !empty($idUserList)) {
                    $query->orWhereIn('user_id', $idUserList);
                }
            });
            $photos =  $query->where('id', '<', $max_id)->orderBy('created_at', 'DESC')->get();

            $query = Tag::where('name', '=', $needle); 
            $tags = $query->get();
            foreach ($tags as $tag) { 
                $byTag = $tag->photos;
                foreach ($byTag as $photo) {
                    if($photo->id < $max_id) {
                        $photos = $photos->add($photo);
                    }    
                }                
            }   

            $queryAuthor = \Author::where('name', 'LIKE', '%' . $needle . '%'); 
            $authors = $queryAuthor->get();
            foreach ($authors as $author) { 
                $byAuthor = $author->photos;                
                foreach ($byAuthor as $photo) {
                    if($photo->id < $max_id) {
                        $photos = $photos->add($photo);
                    }    
                }                 
            }

            $query = Institution::where(function($query) use($needle) {
                    $query->where('name', 'LIKE', '%'. $needle .'%');
                    $query->orWhere('acronym', '=',  $needle);
                });
            $institutions =  $query->get(); 
            
            foreach ($institutions as $institution) { 
                $byInstitution = $institution->photos;
                foreach ($byInstitution as $photo) {
                    if($photo->id < $max_id) {
                        $photos = $photos->add($photo);
                    }    
                }
            }  
        }
        $photos = $photos->unique();
        $photos = $photos->sortByDesc('id')->take(20);
        return \Response::json($photos);
	}
}