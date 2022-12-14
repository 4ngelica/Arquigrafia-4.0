<?php
namespace App\Http\Controllers\Evaluations;

use App\Models\Evaluations\Evaluation;
use App\Models\Evaluations\Binomial;
use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\News\News as News;
use App\lib\log\EventLogger;
use Session;
use Auth;
use App\Models\Photos\Photo;
use Carbon\Carbon;
use View;
use Input;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class EvaluationsController extends Controller {

  // public function __construct()
  // {
  //   $this->beforeFilter('auth',
  //     array( 'except' => ['index','show'] ));
  // }

	public function index()
	{
    $evaluation = Evaluation::all();
    return $evaluation;
	}


	public function show($id)
	{
      return \Redirect::to('/home');
	}

  // public function evaluate($photoId )
  // {
  //     if (Session::has('institutionId') ) {
  //       return \Redirect::to('/home');
  //     }
	//
  //     if(isset($_SERVER['QUERY_STRING'])) parse_str($_SERVER['QUERY_STRING'], $query);
  //     if(isset($f)) {
  //       if($f == "sb") $eventContent['object_source'] = 'pelo botão abaixo da imagem';
  //       elseif($f == "c") $eventContent['object_source'] = 'pelo botão abaixo do gráfico';
  //       elseif($f == "g") $eventContent['object_source'] = 'pelo gráfico';
  //     } else $eventContent['object_source'] = 'diretamente';
  //     EventLogger::printEventLogs(null, 'access_evaluation_page', $eventContent, 'Web');
	//
  //     return static::getEvaluation($photoId, Auth::user()->id, true);
  //   }




   // public function viewEvaluation($photoId, $userId)
   // {
   //    return static::getEvaluation($photoId, $userId, false);
   // }

	 public function viewEvaluation($photoId, $userId)
	 {
		 // $result = Cache::remember('showEvaluation_'. $photoId, 60 * 5, function() use ($photoId, $userId) {
			 	$photo = Photo::find($photoId);
				$user = User::find($userId);
				$tags = $photo->tags;

				// $evaluation = ['photo' => $photo, 'user' => $user, 'tags' => $tags];

				$evaluation = Evaluation::where('user_id', $userId)->where('photo_id', $photoId)->get();
				dd($evaluation);

			// 	return $evaluation;
			// });

			// dd($result);

			// return view('new_front.evaluation.show')->with($result);
			return view('new_front.evaluation.show', compact(['photo', 'user', 'tags']));

	 }

	 public function evaluate($photoId)
	 {
			$photo = Photo::find($photoId);
			$user = Auth::user();
			$tags = $photo->tags;

			return view('new_front.evaluation.edit', compact(['photo', 'user', 'tags']));
	 }

   public function showSimilarAverage($photoId) {
      $isOwner = false;
      if (Auth::check()) $userId = Auth::user()->id;
      $photo = Photo::find($photoId);
      if($photo->user_id == $userId ) $isOwner = true;

      return static::getEvaluation($photoId, $userId, $isOwner);
   }

	 private function getEvaluation($photoId, $userId, $isOwner) {

		 $photo = Photo::find($photoId);

     $binomials = Binomial::all()->keyBy('id');
     $average = Evaluation::average($photo->_id);
     $evaluations = null;
     $averageAndEvaluations = null;
     $checkedKnowArchitecture = false;
     $checkedAreArchitecture = false;
     $user = null;
     $follow = true;

     if ($userId != null) {
        $user = User::find($userId);
        if (Auth::check()) {
          if (Auth::user()->following) {
            if (Auth::user()->following->contains($user->id))
                $follow = false;
            else
                $follow = true;
          }
        }

        $averageAndEvaluations= Evaluation::averageAndUserEvaluation($photo->_id, $userId);
        $evaluations =  Evaluation::where("user_id",$userId)
                                  ->where("photo_id", $photo->_id)
                                  ->orderBy("binomial_id", "asc")->get();

        $checkedKnowArchitecture= Evaluation::userKnowsArchitecture($photoId,$userId);
        $checkedAreArchitecture= Evaluation::userAreArchitecture($photoId,$userId);
     }
      return view('evaluation.evaluate',
      [
        'photos' => $photo,
        'owner' => $user,
        'follow' => $follow,
        'tags' => $photo->tags,
        'average' => $average,
        'userEvaluations' => $evaluations,
        'userEvaluationsChart' => $averageAndEvaluations,
        'binomials' => $binomials,
        'architectureName' => Photo::composeArchitectureName($photo->name),
        'similarPhotos'=>Photo::photosWithSimilarEvaluation($average,$photo->id),
        'isOwner' => $isOwner,
        'checkedKnowArchitecture' => $checkedKnowArchitecture,
        'checkedAreArchitecture' => $checkedAreArchitecture
      ]);
	}


   /** saveEvaluation($id) */
  public function store(Request $request, $id)
  {
      if (Auth::check()) {
          $evaluations =  Evaluation::where("user_id", Auth::id())->where("photo_id", $id)->get();
          $input = $request->all();
          if($request->get('knownArchitecture') == true)
              $knownArchitecture = $request->get('knownArchitecture');
          else $knownArchitecture = 'no';

          if($request->get('areArchitecture') == true) $areArchitecture = $request->get('areArchitecture');
          else  $areArchitecture = 'no';

          $i = 0;
          $user_id = Auth::user()->id;
          $evaluation_string = "";
          $evaluation_names = array(
           "Vertical-Horizontal",
           "Opaca-Translúcida",
           "Assimétrica-Simétrica",
           "Simples-Complexa",
           "Externa-Interna",
           "Fechada-Aberta"
          );

          // Pegar do banco as possives métricas
          $binomials = Binomial::all();
          // Fazer um loop por cada e salvar como uma avaliação
          if ($evaluations->isEmpty()) {
              foreach ($binomials as $binomial) {
                $bid = $binomial->id;
                $newEvaluation = Evaluation::create([
                  'photo_id'=> $id,
                  'evaluationPosition'=> $input['value-'.$bid],
                  'binomial_id'=> $bid,
                  'user_id'=> $user_id,
                  'knownArchitecture'=>$knownArchitecture,
                  'areArchitecture'=>$areArchitecture
                  ]);
                $evaluation_string = $evaluation_string . $evaluation_names[$i++] . ": " . $input['value-'.$bid] . ", ";
              }
          EventLogger::printEventLogs($id, 'insert_evaluation', ['evaluation' => $evaluation_string], 'Web');
          }else {
              foreach ($evaluations as $evaluation) {
                  $bid = $evaluation->binomial_id;
                  $evaluation->evaluationPosition = $input['value-'.$bid];
                  $evaluation->knownArchitecture = $knownArchitecture;
                  $evaluation->areArchitecture = $areArchitecture;
                  $evaluation->save();
                  $evaluation_string = $evaluation_string . $evaluation_names[$i++] . ": " . $input['value-'.$bid] . ", ";
              }
              EventLogger::printEventLogs($id, 'edit_evaluation', ['evaluation' => $evaluation_string], 'Web');
          } //end if evaluation empty
          return \Redirect::to("/evaluations/{$id}/evaluate")->with('message',
              '<strong>Interpretação salva com sucesso</strong><br>Abaixo você pode visualizar a média atual de interpretações');
      } else { // avaliação sem login
          return \Redirect::to("/photos/{$id}")->with('message',
            '<strong>Erro na avaliação</strong><br>Faça login para poder avaliar');
      }//End if check
  }




}
