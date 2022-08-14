<?php

/* Evaluations */
Route::get('/evaluations','modules\evaluations\controllers\EvaluationsController@index');
Route::get('/evaluations/{photo_id}/evaluate','modules\evaluations\controllers\EvaluationsController@evaluate');
Route::get('/evaluations/{photo_id}/viewEvaluation/{user_id}','modules\evaluations\controllers\EvaluationsController@viewEvaluation'); 
Route::get('/evaluations/{photo_id}/showSimilarAverage/', 'modules\evaluations\controllers\EvaluationsController@showSimilarAverage'); 
//Route::post('/evaluations/{photo_id}/saveEvaluation','modules\evaluations\controllers\EvaluationsController@saveEvaluation');
Route::post('/evaluations/{photo_id}','modules\evaluations\controllers\EvaluationsController@store');
Route::resource('/evaluations','modules\evaluations\controllers\EvaluationsController');
