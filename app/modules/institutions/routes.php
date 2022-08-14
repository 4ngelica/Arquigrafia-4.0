<?php

/* INSTITUTIONS */
Route::get('/institutions/{id}', 'modules\institutions\controllers\InstitutionsController@show');
Route::get('/institutions/{id}/edit', 'modules\institutions\controllers\InstitutionsController@edit');
Route::get('/institutions/form/upload','modules\institutions\controllers\InstitutionsController@formPhotos');
Route::post('/institutions/save','modules\institutions\controllers\InstitutionsController@saveFormPhotos');
Route::get('/institutions/{photo_id}/form/edit','modules\institutions\controllers\InstitutionsController@editFormPhotos');
Route::put('/institutions/{photo_id}/update/photo','modules\institutions\controllers\InstitutionsController@updateFormPhotos');

Route::get('/institutions/{id}/allphotos', 'modules\institutions\controllers\InstitutionsController@allImages');

/* FOLLOW */
Route::get('/friends/followInstitution/{institution_id}', 'modules\institutions\controllers\InstitutionsController@followInstitution');
Route::get('/friends/unfollowInstitution/{institution_id}', 'modules\institutions\controllers\InstitutionsController@unfollowInstitution');

Route::resource('/institutions','modules\institutions\controllers\InstitutionsController');



