<?php

Route::get('/notifications', 'modules\notifications\controllers\NotificationsController@show');
Route::get('/markRead/{id}', 'modules\notifications\controllers\NotificationsController@read');
Route::get('/readAll',       'modules\notifications\controllers\NotificationsController@readAll');

Route::get('/refreshBubble', 'NotificationsController@howManyUnread');
Event::subscribe('modules\notifications\subscriber\NotificationSubscriber');