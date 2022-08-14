<?php
  use modules\news\models\News as News;
  use modules\collaborative\models\Tag as Tag;
  use modules\collaborative\models\Comment as Comment;
  use modules\collaborative\models\Like as Like;
  use modules\gamification\models\Leaderboard as Leaderboard;
  use modules\evaluations\models\Evaluation as Evaluation;
  use modules\evaluations\models\Binomial as Binomial;
  use modules\institutions\models\Institution as Institution;
  use modules\institutions\models\Employee as Employee;

  User::updating(function($user)
  {  \Log::info("foto=".Input::hasFile('photo'));
    if(Input::hasFile('photo')){          
          News::eventNewPicture($user,'new_profile_picture'); 
    }elseif (Input::has('txtPicture')) {
      if(trim(Input::get('txtPicture')) == "picture"){             
              News::eventGetFacebookPicture($user,'new_profile_picture');
      }
    }else{
         News::eventUpdateProfile($user,'edited_profile'); 
    }      
  });

   /*Module Gamification */
  User::created (function ($user) {
      Leaderboard::createFromUser($user);
  });

  /*Modules Gamification and Institution*/
  Photo::created (function ($photo) {
    if (!$photo->hasInstitution() ) {
        News::eventNewPhoto($photo, 'new_photo');
        //gamifications
        Leaderboard::increaseUserScore($photo->user_id, 'uploads');
    }else{
        //institutions
        if($photo->draft == NULL)
           News::registerPhotoInstitutional($photo,'new_institutional_photo');
    }
  });
  

//
  Photo::updated(function ($photo) {
    if (!$photo->hasInstitution() ) {
          News::eventUpdatePhoto($photo, 'edited_photo');
    }
  });

  //Gamification Related
  Photo::deleted (function ($photo) {
    if ( ! $photo->hasInstitution() ) {
      Leaderboard::decreaseUserScore($photo->user_id, 'uploads');
      //delete activities in photo
      //News::deletePhotoActivities($photo,'delete');
    }
    Leaderboard::decreaseUsersScores($photo->evaluators, 'evaluations');
  });

  Photo::deleting(function ($photo) {
    if ( ! $photo->hasInstitution() ) {      
      //delete activities in photo
      News::deletePhotoActivities($photo,'delete');
    }
    
  });

//Binomial and Gamification Related
  Evaluation::created (function ($evaluation) {
    $min_id = Binomial::orderBy('id', 'asc')->first();
    if ( $evaluation->binomial_id == $min_id->id ) {
      Leaderboard::increaseUserScore($evaluation->user_id, 'evaluations');
      News::registerPhotoEvaluated($evaluation,'evaluated_photo'); 
    }
  });

  /*Collaborative Modules*/
  Like::created (function ($likes) { 
    if($likes->likable_type == 'Photo')
      News::eventLikedPhoto($likes,'liked_photo'); 
  });

  Comment::created (function ($comment) { 
      News::eventCommentedPhoto($comment,'commented_photo'); 
  });