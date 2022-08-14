<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteWorkauthorOfPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( Schema::hasColumn('photos', 'workAuthor') )
			Schema::table('photos', function(Blueprint $table)
			{
				$table->dropColumn('workAuthor');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{	

    	Schema::table('photos', function($table)
        {   
            $table->string('workAuthor', 950)->nullable();       
        });

        $results = DB::table('photo_author')->select('photo_id', 'author_id')->orderBy('photo_id', 'asc')->get();
        $stringAuthor = "";
        foreach ($results as $result) {
        	$authors = Author::find($result->author_id); 
        	$photo = Photo::find($result->photo_id);       	

        	if($photo->workAuthor != null || !empty($photo->workAuthor)){         		
        		$stringAuthor = mb_strtolower(trim($photo->workAuthor))."; ".mb_strtolower(trim($authors->name));
				
        	}else{
        		$stringAuthor = mb_strtolower(trim($authors->name));
        	}  
        	Photo::where('id', '=', $result->photo_id)->update(array('workAuthor' => $stringAuthor));   	
        }
	}

}
