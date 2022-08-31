<?php

namespace App\Models\Photos;

use App\Models\Photos\Author;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Author extends Eloquent {

  public $timestamps = false;

  protected $fillable = ['name'];

  public function photos() {
    return $this->belongsToMany('Photo', 'photo_author', 'author_id', 'photo_id');
  }

  public static function getOrCreate($name)
  {
    $author = Author::where('name', $name)->first();
    if(is_null($author)){
      $author = new Author();
      $author->name = $name;
      $author->approved = 0;
      $author->save();
    }
    return $author;
  }

  public static function formatAuthors($raw_author) {
    $authors = explode(';', $raw_author);
    $authors = array_map('trim', $authors);
    $authors = array_map('mb_strtolower', $authors);
    $authors = array_filter($authors);
    return array_unique($authors);
  }

  public function saveAuthors($authors_list,$photo)
  {
    $arrayAuthors = $this->formatAuthors($authors_list);
    $authors = [];
    foreach ($arrayAuthors as $name_author) {
      $author = $this->getOrCreate($name_author);
      $authors[] = $author->id;
    }
    $photo->authors()->sync($authors, false);
  }

  public function getAuthorPhoto($photo_id){
    $allAuthors = DB::table('photo_author')
      ->select('author_id')
      ->where('photo_id',$photo_id)
      ->pluck('author_id');
     $authorsList = Author::wherein('id',$allAuthors)->get();

     return $authorsList;
  }

  public function deleteAuthorPhoto($photo){
      $allAuthors = $this->getAuthorPhoto($photo->id);
      if(!empty($allAuthors)){
          foreach ($allAuthors as $allAuthor) {
            $photo->authors()->detach($allAuthor->id);
          }
      }

  }
  public function updateAuthors($authors_list,$photo)
  {
      $this->deleteAuthorPhoto($photo);
      $this->saveAuthors($authors_list,$photo);
  }

  public static function transform($raw_authors) {
    $authors = explode(';', $raw_authors);
    $authors = array_map('trim', $authors);
    $authors = array_filter($authors);
    return array_unique($authors);
  }

}
