<?php

namespace App\Models\Users;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\lib\date\Date;
use App\lib\log\EventLogger;
use Cmgmyr\Messenger\Traits\Messagable;
use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Traits\Gamification\UserGamificationTrait;
use App\Models\Institutions\Institution;
use App\Models\Collaborative\Comment;
use App\Models\Collaborative\Like;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable {

	protected $connection = 'mongodb';
	protected $collection = 'users';

	protected $casts  = [
		'id' => 'string'
	];

	// use UserTrait, RemindableTrait;

	use UserGamificationTrait;

	use Messagable;

	protected $fillable = ['id','name','email','password','login','verify_code'];

	protected $date;

	use HasApiTokens, HasFactory, Notifiable;

	// public function __construct($attributes = array(), Date $date = null) {
	// 	parent::__construct($attributes);
	// 	$this->date = $date ?: new Date;
	// }

	public function news()
    {
        return $this->hasMany('App\Models\News\News');
    }

	public function notifications()
 	{
    	return $this->hasMany('\Tricki\Notification\Models\NotificationUser');
  	}

	public function photos()
	{
		return $this->hasMany('App\Models\Photos\Photo')->whereNull('institution_id');
	}
	public function userPhotos($user_id){
		return $this->hasMany('App\Models\Photos\Photo')->where('user_id', $user_id)->whereNull('institution_id');
	}
	public function comments()
	{
		return $this->hasMany('App\Models\Collaborative\Comment');
	}
	public function evaluations()
	{
		return $this->hasMany('App\Models\Evaluations\Evaluation');
	}

	public function likes()
	{
		return $this->hasMany('App\Models\Collaborative\Like');
	}

	public function albums()
	{
		return $this->hasMany('App\Models\Albums\Album');
	}
	public function userAlbums()
	{
		return $this->hasMany('App\Models\Albums\Album')->whereNull('institution_id');
	}
	public function occupation()
	{
		return $this->hasOne('App\Models\Users\Occupation');
	}

	public function suggestions()
	{
		return $this->hasMany('App\Models\Moderation\Suggestion');
	}

	public function moderator()
	{
		return $this->belongsTo('App\Models\Moderation\Suggestion');
	}

	//seguidores
	public function followers()
	{
		return $this->belongsToMany('App\Models\Users\User', null, 'followed_id', 'following_id');
	}

	//seguindo
	public function following()
	{
		return $this->belongsToMany('App\Models\Users\User', null, 'following_id', 'followed_id');
	}

	public function institutions(){
		return $this->belongsToMany('App\Models\Institutions\Institution', 'friendship_institution','institution_id', 'following_user_id');
	}

	public function followingInstitution(){
		return $this->belongsToMany('App\Models\Institutions\Institution', 'friendship_institution','following_user_id', 'institution_id');
	}

	public function roles()
	{
		return $this->belongsToMany('App\Models\Users\Role', 'users_roles');
	}


	protected $hidden = array('password', 'remember_token');

	public static function checkOldAccount( $user, $password)
	{
		$verify = exec('java -cp "' . public_path() . '/java:' . public_path() . '/java/jasypt-1.7.jar" PasswordValidator ' . $password . ' ' . $user->password);
		if ( strcmp($verify, 'true') == 0 ) return true;
		return false;
	}

	public static function stoa($stoa_user) {

		$user = User::where('login', 'stoa_' . $stoa_user->nusp)->first();

		if (!$user) {
			$user = User::newStoaUser($stoa_user);
		}

		if ($stoa_user->image_base64) {
			User::saveProfileImage($user, $stoa_user->image_base64);
		}

		return $user;
	}

	private static function newStoaUser($stoa_user) {
		$user = new User();
		$user->name = $stoa_user->first_name;
		$user->email = $stoa_user->email;
		$user->password = 'stoa';
		$user->login = 'stoa_' . $stoa_user->nusp;
		$user->id_stoa = 'stoa_' . $stoa_user->nusp;
		if ($stoa_user->surname)
			$user->name = $user->name . ' ' . $stoa_user->surname;
		if ($stoa_user->homepage)
			$user->site = $stoa_user->homepage;
		$user->save();

		EventLogger::printEventLogs(null, "new_account", ["origin" => "Stoa"], "Web");
		return $user;
	}

	private static function saveProfileImage($user, $image) {
		$user->photo = "/arquigrafia-avatars/".$user->id.".jpg";
		$user->save();
		$image = Image::make(base64_decode($image))->encode('jpg', 80);
		$image->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
		$image->save(public_path().'/arquigrafia-avatars/'. $user->id."_original.jpg");
	}

	public function equal($user) {
		try {
			return $user instanceof User &&
				$this->id == $user->id;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function userInformation($login){
		$user = User::where('email', $login)->orWhere('username', $login)->take(1)->get();
    return $user;
	}


	public static function userInformationObtain($email){
		$user = User::where('email','=',$email)->whereRaw('(id_stoa is NULL or id_stoa != login) and (id_facebook is NULL or id_facebook != login)')->first();
          return $user;
	}

	public static function userVerifyCode($verify_code){
		$newUser = User::where('verify_code','=',$verify_code)->first();
        return $newUser;
	}

	public static function userBelongInstitution($login,$institution){

		Log::info("Begining userBelongInstitution with input params login=".$login.", institution=".$institution);

		$employees = DB::table('employees')
    			->join('users','employees.user_id','=','users.id')
    			->join('institutions','employees.institution_id','=','institutions.id')
    			->select('institutions.id')
     			->where('employees.institution_id', $institution)
     			->where('users.login',$login)
     			->orWhere('users.email',$login)
     			->get();

     			if (!empty($employees)){
     				return true;
     			}else{
     				return false;
     			}


	}

	// public function setBirthdayAttribute($birthday) {
	// 	$this->attributes['birthday'] = $this->date->formatDate($birthday);
	// }

	public function updateAccount($password) {
		$this->oldAccount = 0;
		$this->password = Hash::make($password);
		$this->save();
	}

}
