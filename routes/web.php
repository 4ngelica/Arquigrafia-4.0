<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Photos\PagesController;
use App\Http\Controllers\Photos\PhotosController;
use App\Http\Controllers\Photos\VideosController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Albums\AlbumsController;
use App\Http\Controllers\Moderation\ContributionsController;
use App\Http\Controllers\Moderation\SuggestionsController;
use App\Http\Controllers\Institutions\ImportsController;
use App\Http\Controllers\Institutions\InstitutionsController;
use App\Http\Controllers\AudiosController;
use App\Http\ControllersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OAMController;
use App\Http\Controllers\Evaluations\EvaluationsController;
use App\Http\Controllers\Collaborative\CommentsController;
use App\Http\Controllers\Collaborative\FollowController;
use App\Http\Controllers\Collaborative\GroupsController;
use App\Http\Controllers\Collaborative\LikesController;
use App\Http\Controllers\Collaborative\ReportsController;
use App\Http\Controllers\Collaborative\TagsController;
use App\Http\Controllers\Gamification\QuestionsController;
use App\Http\Controllers\Scores\ScoresController;
use App\Http\Controllers\Drafts\DraftsController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//novo front
Route::get('/images/{count}', [PagesController::class, 'images']);
Route::get('/novaHome', [PagesController::class, 'novaHome']);

// Route::prefix('/NovoSearch')->group(function () {
//   Route::get('/', [PagesController::class, 'NovoSearch']);
//   Route::post('/', [PagesController::class, 'NovoSearch'])->name('search');
//   Route::get('/more', [PagesController::class, 'NovoAdvancedSearch']);
// });

Route::prefix('/novoUsers')->group(function () {
  Route::get('/{id}', [PagesController::class, 'NovoUsersShow'])->name('users.show');
});

Route::prefix('/novoPhotos')->group(function () {
  Route::get('/{id}', [PagesController::class, 'NovoPhotosShow'])->name('photos.show');
});
//fim do novo front


//front atual
Route::get('/', [PagesController::class, 'main']);

Route::get('/photos/import', [ImportsController::class, 'import']);

/* phpinfo() */
Route::get('/info/', function(){ return view('i'); });

Route::get('/landing/{language?}', [PagesController::class, 'landing']);
Route::get('/home', [PagesController::class, 'home']);
// Route::get('/panel', [PagesController::class, 'panel']);
Route::get('/project', function() { return view('project'); });
Route::get('/faq', function() { return view('faq'); });
Route::get('/chancela', function() { return view('chancela'); });
Route::get('/termos', function() { return view('termos'); });

/* SEARCH */
Route::prefix('/search')->group(function () {
  Route::get('/', [PagesController::class, 'search']);
  Route::post('/', [PagesController::class, 'search'])->name('search');
  Route::get('/more', [PagesController::class, 'advancedSearch']);
});

// Route::prefix('/search')->group(function () {
//
// });

/* USERS */
Route::prefix('/users')->group(function () {
  Route::get('/account', [UsersController::class, 'account']);
  Route::get('/register/', [UsersController::class, 'emailRegister']);
  Route::get('/verify/{verifyCode}',[UsersController::class, 'verify']);
  Route::get('/verify/',[UsersController::class, 'verifyError']);
  Route::get('/login', [UsersController::class, 'loginForm']);
  Route::post('/login', [UsersController::class, 'login']);
  Route::post('/institutionalLogin', [UsersController::class, 'institutionalLogin'])->name('institutional.login');
  Route::get('/logout', [UsersController::class, 'logout']);
  Route::get('/forget', [UsersController::class, 'forgetForm']);
  Route::post('/forget', [UsersController::class, 'forget']);
  Route::get('/{id}', [UsersController::class, 'show']);
  Route::get('/{id}/edit', [UsersController::class, 'edit']);
  // Route::put('/{id}', [UsersController::class, 'store']);
  Route::get('/', [UsersController::class, 'index']);
  Route::post('/', [UsersController::class, 'store'])->name('register');
});
Route::get('/getPicture', [UsersController::class, 'getFacebookPicture']);

/* FOLLOW */
Route::prefix('/friends')->group(function () {
  Route::get('/follow/{user_id}', [UsersController::class, 'follow']);
  Route::get('/unfollow/{user_id}', [UsersController::class, 'unfollow']);
});

// AVATAR
Route::get('/profile/10/showphotoprofile/{profile_id}', [UsersController::class, 'profile']);

/* ALBUMS */
Route::resource('/albums',AlbumsController::class);
Route::get('/albums/photos/add', [AlbumsController::class, 'paginateByUser']);
Route::get('/albums/{id}/photos/rm', [AlbumsController::class, 'paginateByAlbum']);
Route::get('/albums/{id}/photos/add', [AlbumsController::class, 'paginateByOtherPhotos']);
Route::get('/albums/get/list/{id}', [AlbumsController::class, 'getList']);
Route::post('/albums/photo/add', [AlbumsController::class, 'addPhotoToAlbums']);
Route::delete('/albums/{album_id}/photos/{photo_id}/remove', [AlbumsController::class, 'removePhotoFromAlbum']);

/* ALBUMS - ajax */
Route::get('/albums/get/cover/{id}', [AlbumsController::class, 'paginateCoverPhotos']);
Route::post('/albums/{id}/update/info', [AlbumsController::class, 'updateInfo']);
Route::post('/albums/{id}/detach/photos', [AlbumsController::class, 'detachPhotos']);
Route::post('/albums/{id}/attach/photos', [AlbumsController::class, 'attachPhotos']);
Route::get('/albums/{id}/paginate/photos', [AlbumsController::class, 'paginateAlbumPhotos']);
Route::get('/albums/{id}/paginate/other/photos', [AlbumsController::class, 'paginatePhotosNotInAlbum']);

Route::get('/photos/batch', [PhotosController::class, 'batch']);
Route::get('/photos/upload',[PhotosController::class, 'form']);
Route::get('/photos/migrar',[PhotosController::class, 'migrar']);
Route::get('/photos/rollmigrar',[PhotosController::class, 'rollmigrar']);
Route::get('/photos/download/{photo_id}',[PhotosController::class, 'download']);
Route::get('/photos/completeness', [PhotosController::class, 'showCompleteness']);
Route::get('/photos/to_complete', [PhotosController::class, 'getCompletenessPhotos']);

Route::get('/photos/{id}', [PhotosController::class, 'show']);

/* SEARCH PAGE */
Route::get('/search/paginate/other/photos', [PagesController::class, 'paginatePhotosResult']);
Route::get('/search/more/paginate/other/photos', [PagesController::class, 'paginatePhotosResultAdvance']);

/* OAM */
Route::get('oam', [OAMController::class, 'index']);
Route::get('oam/place', [OAMController::class, 'place']);
Route::get('oam/photo/{id}', [OAMController::class, 'photo']);

/* AUDIO */
Route::post('oam/audios', [OAMController::class, 'storeAudio']);

/* Evaluations */
Route::get('/evaluations', [EvaluationsController::class, 'index']);
Route::get('/evaluations/{photo_id}/evaluate',[EvaluationsController::class, 'evaluate']);
Route::get('/evaluations/{photo_id}/viewEvaluation/{user_id}',[EvaluationsController::class, 'viewEvaluation']);
Route::get('/evaluations/{photo_id}/showSimilarAverage/', [EvaluationsController::class, 'showSimilarAverage']);
//Route::post('/evaluations/{photo_id}/saveEvaluation','App\Http\Controllers\EvaluationsController@saveEvaluation');
Route::post('/evaluations/{photo_id}',[EvaluationsController::class, 'store']);
// Route::resource('/evaluations',[EvaluationsController::class]);

//Moderation

Route::post('/suggestions', [SuggestionsController::class, 'store']);
Route::post('/suggestions/sent', [SuggestionsController::class, 'sendNotification']);
Route::get('/suggestions', [SuggestionsController::class, 'edit']);
// Route::post('/suggestions', [SuggestionsController::class, 'update']);
// Route::post('users/suggestions', [SuggestionsController::class, 'update']);

// Route::get('/contributions', [ContributionsController::class, 'showContributions']);
Route::get('/contributions', [ContributionsController::class, 'showContributions']);

// Route::resource('/users/contributions', ContributionsController::class);


// JSON Responses Routes
Route::get('/suggestions/user_suggestions', [SuggestionsController::class, 'getUserSuggestions']);
Route::get('/suggestions/user_statistics', [SuggestionsController::class, 'getUserSuggestionsStatistics']);

/* INSTITUTIONS */
Route::get('/institutions/{id}', [InstitutionsController::class, 'show']);
Route::get('/institutions/{id}/edit', [InstitutionsController::class, 'edit']);
Route::get('/institutions/form/upload', [InstitutionsController::class, 'formPhotos']);
Route::post('/institutions/save', [InstitutionsController::class, 'saveFormPhotos']);
Route::get('/institutions/{photo_id}/form/edit', [InstitutionsController::class, 'editFormPhotos']);
Route::put('/institutions/{photo_id}/update/photo', [InstitutionsController::class, 'updateFormPhotos']);

Route::get('/institutions/{id}/allphotos', [InstitutionsController::class, 'allImages']);

/* FOLLOW */
Route::get('/friends/followInstitution/{institution_id}', [InstitutionsController::class, 'followInstitution']);
Route::get('/friends/unfollowInstitution/{institution_id}', [InstitutionsController::class, 'unfollowInstitution']);

Route::resource('/institutions', InstitutionsController::class);

/* TAGS */
Route::get('/tags/json', [TagsController::class,'index']);
Route::get('/tags/refreshCount', [TagsController::class,'refreshCount']);

/* COMMENTARIOS */
Route::post('/comments/{photo_id}', [CommentsController::class,'comment']);
Route::get('/comments/{comment_id}/like', [CommentsController::class,'commentLike']);
Route::get('/comments/{comment_id}/dislike', [CommentsController::class,'commentDislike']);
// Route::resource('/comments', CommentsController::class);

/* LIKE E DISLIKE */
Route::get('/like/{id}', [LikesController::class,'photoLike']);
Route::get('/dislike/{id}', [LikesController::class,'photoDislike']);
Route::resource('/likes', LikesController::class);

/* GRUPOS */
Route::resource('/groups',GroupsController::class);

/*EVENTS */
Event::subscribe('App\Http\Events\Subscriber\LikeSubscriber');
/*REPORTs*/
Route::post('/reports/photo', [ReportsController::class,'reportPhoto']);
Route::get('/reports/showModalReport/{id}', [ReportsController::class,'showModalReportPhoto']);
//Route::get('/photos/showModalReport/{id}', 'ReportController@showModalReportPhoto');

/* GAMIFICATION */
// Route::get('/photos/{id}/get/field', [QuestionsController::class,'getField']);
// Route::post('/photos/{id}/set/field', [QuestionsController::class,'setField']);
// Route::get('/rank/get', [ScoresController::class,'getRankEval']);
// Route::get('/leaderboard', [ScoresController::class,'getLeaderboard']);
// Route::get('/badges/{id}', [BadgesController::class,'show']);

/* LOG */
// Route::post('/logs', 'modules\logs\controllers\LogsController@create');

/* DRAFTS */
Route::post('/drafts/delete', [DraftsController::class,'deleteDraft']);
Route::get( '/drafts/paginate', [DraftsController::class,'paginateDrafts']);
Route::get( '/drafts/{id}', [DraftsController::class,'getDraft']);
Route::get( '/drafts', [DraftsController::class,'listDrafts']);


/*Notifications*/
// Route::get('/notifications', 'modules\notifications\controllers\NotificationsController@show');
// Route::get('/markRead/{id}', 'modules\notifications\controllers\NotificationsController@read');
// Route::get('/readAll',       'modules\notifications\controllers\NotificationsController@readAll');
//
// Route::get('/refreshBubble', 'NotificationsController@howManyUnread');
Event::subscribe('App\Http\Events\Subscriber\NotificationSubscriber');
