<?php

/* GAMIFICATION */
Route::get('/photos/{id}/get/field', 'modules\gamification\controllers\QuestionsController@getField');
Route::post('/photos/{id}/set/field', 'modules\gamification\controllers\QuestionsController@setField');
Route::get('/rank/get', 'modules\gamification\controllers\ScoresController@getRankEval');
Route::get('/leaderboard', 'modules\gamification\controllers\ScoresController@getLeaderboard');
Route::get('/badges/{id}', 'modules\gamification\controllers\BadgesController@show');