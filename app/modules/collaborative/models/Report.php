<?php
namespace modules\collaborative\models;
use User;
use Photo;

class Report extends \Eloquent {

  public $timestamps = true;

  protected $table = "reports";
  protected $fillable = [ 'photo_id', 'report_type_data', 'user_id', 'observation','report_type' ];
  
  public function user()
  {
    return $this->belongsTo('User');
  }

  public function photo()
  {
    return $this->belongsTo('Photo');
  }
  
  public static function getFirstOrCreate($user, $photo, $dataType, $observation,$reportType) {
    return self::firstOrCreate([
        'user_id' => $user->id,
        'photo_id' => $photo->id,
        'report_type_data' => $dataType,
        'observation' => $observation,
        'report_type'=>$reportType,

      ]);
  }

}