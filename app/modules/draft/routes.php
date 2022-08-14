<?php 

/* DRAFTS */
Route::post('/drafts/delete'  , 'modules\draft\controllers\DraftsController@deleteDraft');
Route::get( '/drafts/paginate', 'modules\draft\controllers\DraftsController@paginateDrafts');
Route::get( '/drafts/{id}'    , 'modules\draft\controllers\DraftsController@getDraft');
Route::get( '/drafts'         , 'modules\draft\controllers\DraftsController@listDrafts');