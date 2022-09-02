<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(array('middleware' => 'cors', 'prefix' => 'api/'), function()
{
 /* Controlador de fotos, usuários e adjacentes */
    Route::resource('photos', 'modules\api\controllers\APIPhotosController');
    Route::resource('users', 'modules\api\controllers\APIUsersController');
    Route::resource('tags'     , 'modules\api\controllers\APITagsController');
    Route::resource('authors'  , 'modules\api\controlles\APIAuthorsControllers');
    /* Denuncias */
    Route::post('photos/report', 'modules\api\controllers\APIReportController@report');
    /* Controlador de autenticação */
    Route::post('login', 'modules\api\controllers\APILogInController@verify_credentials');
    Route::post('login_facebook', 'modules\api\controllers\APILogInController@verify_credentials_facebook');
    Route::post('auth', 'modules\api\controllers\APILogInController@validate_mobile_token');
    Route::post('logout', 'modules\api\controllers\APILogInController@log_out');
    /* Controlador de feed */
    Route::get('feed/{id}', 'modules\api\controllers\APIFeedController@loadFeed');
    Route::get('loadMore/{id}', 'modules\api\controllers\APIFeedController@loadMoreFeed');
    /* Controlador de perfis */
    Route::get('profile/{id}', 'modules\api\controllers\APIProfilesController@getProfile');
    Route::get('userPhotos/{id}', 'modules\api\controllers\APIProfilesController@getUserPhotos');
    Route::get('moreUserPhotos/{id}', 'modules\api\controllers\APIProfilesController@getMoreUserPhotos');
    Route::get('profile/{id}/followers', 'modules\api\controllers\APIProfilesController@getFollowers');
    Route::get('profile/{id}/following', 'modules\api\controllers\APIProfilesController@getFollowing');
    Route::get('profile/{id}/evaluatedPhotos', 'modules\api\controllers\APIProfilesController@getUserEvaluations');
    Route::get('profile/{id}/moreEvaluatedPhotos', 'modules\api\controllers\APIProfilesController@getMoreUserEvaluations');
    /* Controlador de avaliações */
    Route::get('photos/{photoId}/evaluation/{userId}', 'modules\api\controllers\APIEvaluationController@retrieveEvaluation');
    Route::post('photos/{photoId}/evaluation/{userId}', 'modules\api\controllers\APIEvaluationController@storeEvaluation');
    Route::get('photos/{photoId}/averageEvaluation/{userId}', 'modules\api\controllers\APIEvaluationController@averageEvaluationValues');
    /* Controlador de busca */
    Route::get('recent', 'modules\api\controllers\APIFeedController@loadRecentPhotos');
    Route::get('moreRecent', 'modules\api\controllers\APIFeedController@loadMoreRecentPhotos');
    Route::post('search', 'modules\api\controllers\APISearchController@search');
    Route::post('moreSearch', 'modules\api\controllers\APISearchController@moreSearch');

});
