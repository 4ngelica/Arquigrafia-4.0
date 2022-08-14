<?php

Route::post('/suggestions', 'modules\moderation\controllers\SuggestionsController@store');
Route::post('/suggestions/sent', 'modules\moderation\controllers\SuggestionsController@sendNotification');
Route::get('/users/suggestions', 'modules\moderation\controllers\SuggestionsController@edit');
Route::post('/users/suggestions', 'modules\moderation\controllers\SuggestionsController@update');
Route::get('/users/contributions', 'modules\moderation\controllers\ContributionsController@showContributions');

// JSON Responses Routes
Route::get('/suggestions/user_suggestions', 'modules\moderation\controllers\SuggestionsController@getUserSuggestions');
Route::get('/suggestions/user_statistics', 'modules\moderation\controllers\SuggestionsController@getUserSuggestionsStatistics');
