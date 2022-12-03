<?php
namespace App\Models\Collaborative;

use App\Models\Institutions\Institution;
use App\Models\Photos\Photo;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\Model as Model;
use Session;

class TagAssignments extends Model {

  public $timestamps = false;

  protected $fillable = ['tag_id', 'photo_id'];
  protected $connection = 'mongodb';
  protected $collection = 'tag_assignments';
}
