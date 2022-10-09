<?php
namespace App\Models\Collaborative;

use App\Models\Users\User;
use App\Models\Photos\Photo;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Report extends Model {

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
