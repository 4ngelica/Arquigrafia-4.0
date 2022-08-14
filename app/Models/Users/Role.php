<?php

class Role extends Eloquent {

  public $timestamps = false;

  protected $fillable = ['name'];

  public function users() {
     return $this->belongsToMany('User', 'users_roles', 'user_id', 'role_id');
  }

  public static function usersRoles() {
  }

  
  public static function proba(){
      $string = "Ok";
      return $string;
  }
 

}