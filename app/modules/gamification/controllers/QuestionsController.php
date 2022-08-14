<?php 
namespace modules\gamification\controllers;

class QuestionsController extends \BaseController {

  public function __construct()
  {
    $this->beforeFilter('auth',
      array( 'except' => [] ));
  }

  public function getField($id) {
    $photo = \Photo::find($id);
    if ( is_null($photo) || !$photo->user->equal(\Auth::user()) ) {
      return \Response::json('fail');
    }
    $field = $photo->getEmptyField(\Input::get('fp'));
    $question = $photo->getFieldQuestion($field);
    if ( empty($field) && empty($question) ) {
      return \Response::json([
        'end' => true,
        'complete' => empty($photo->empty_fields)
      ]);
    }
    return \Response::json([ 'end' => false, 'field' => $field, 'question' => $question ]);
  }

  public function setField($id) {
    $photo = \Photo::find($id);
    $field = \Input::get('field');
    $value = \Input::get($field);
    if ( is_null($photo) || !$photo->user->equal(\Auth::user()) ||
      empty($value) || !empty($photo->$field) ) {
        return \Response::json('fail');
    }
    $photo->$field = $value;
    $photo->save();
    return \Response::json([
      'field' => $field,
      'information_completion' => $photo->information_completion,
      'is_address' => in_array($field, ['street', 'district', 'city', 'state', 'country']),
      'html' => \View::make('photos.fields.info')
        ->with(['photo' => $photo, 'field' => $field])
        ->render()
    ]);
  }

}