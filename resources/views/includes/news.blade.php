<?php 
  $news = DB::table('news')->where('user_id', '=', Auth::user()->id)->orWhere('user_id', '=', 0)->orderBy('updated_at', 'desc')->take(6)->get(); 
  ?>
  @foreach($news as $info)
  	<?php $photos = DB::table('photos')->where('id', '=', $info->object_id)->first(); ?> 
	@if($info->news_type == 'new_photo')
	        
	<!--Alguém que você segue inseriu uma foto-->
	<div class="gallery_box">

	  	<a href='{{ URL::to("/photos") . "/" . $info->object_id }}'> 
	  		@if($photos->type == "video")
	  			<div class="iconVideo"></div>
	  			<iframe width="560" height="315" src="{{$photos->video}}" frameborder="0" allowfullscreen></iframe>
	  		@else
	  			<img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
	  		@endif
	      
	    </a>
		  <a href='{{ URL::to("/photos") . "/" . $info->object_id }}' class="name">
		      {{User::find($info->sender_id)->name}} 
			@if($photos->type == "video")	
                                postou um novo vídeo
                        @else		
				postou uma nova imagem
			@endif
	 	</a>
			  <br />
	</div>
	@elseif($info->news_type == 'new_institutional_photo')<!--Uma instituição inseriu uma foto-->
			  <div class="gallery_box">
			    <a href='{{ URL::to("/photos") . "/" . $info->object_id }}'>  
			    	@if($photos->type == "video")
	  					<div class="iconVideo"></div>
               			<iframe width="560" height="315" src="{{$photos->video}}" frameborder="0" allowfullscreen></iframe>
               		@else	
				<?php if (Photo::find($info->object_id) != null) { ?>
			        	<img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
	<?php } ?> 
	 @endif
				</a>
				<a href='{{ URL::to("/photos") . "/" . $info->object_id }}' class="name">
				        A instituição {{modules\institutions\models\Institution::find($info->sender_id)->name}} 
				 @if($photos->type == "video")
					postou um novo vídeo
				 @else
					postou uma nova imagem
				 @endif
				</a>
				<br />
				</div>
	@elseif($info->news_type == 'commented_photo')<!--Alguém que você segue comentou uma foto-->
					      
					     @if($info->secondary_type == NULL && $info->tertiary_type == NULL)
	  <div class="gallery_box">
	      <a href='{{ URL::to("/photos") . "/" . modules\collaborative\models\Comment::find($info->object_id)->photo_id . "#" . $info->object_id}}'>                 
	            <img src={{"/arquigrafia-images/" . modules\collaborative\models\Comment::find($info->object_id)->photo_id . "_home.jpg"}} title="{{ Photo::find(modules\collaborative\models\Comment::find($info->object_id)->photo_id)->name }}" class="gallery_photo" />
		        </a>
			    <a href='{{ URL::to("/photos") . "/" . modules\collaborative\models\Comment::find($info->object_id)->photo_id . "#" . $info->object_id}}' class="name">
			          @if($info->data == null)
	        {{User::find($info->sender_id)->name}} comentou nesta imagem
		      @else
		              <?php 
			                $users = explode(":", $info->data);
					          $users_size = count($users);
						          ?>
							          {{$users_size}} usuários comentaram nesta imagem
								        @endif
									    </a>
									        <br />
										  </div>
										    @else
										      <div class="gallery_box">
										          <a href='{{ URL::to("/photos") . "/" . modules\collaborative\models\Comment::find($info->object_id)->photo_id }}'>                 
											        <img src={{"/arquigrafia-images/" . modules\collaborative\models\Comment::find($info->object_id)->photo_id . "_home.jpg"}} title="{{ Photo::find(modules\collaborative\models\Comment::find($info->object_id)->photo_id)->name }}" class="gallery_photo" />
												    </a>
												        <a href='{{ URL::to("/photos") . "/" . modules\collaborative\models\Comment::find($info->object_id)->photo_id }}' class="name">
													      @if($info->data == null)
	        {{User::find($info->sender_id)->name}} realizou diversas ações nesta imagem. Confira!
		      @else
		              Diversos usuários realizaram diversas ações nesta imagem. Confira!
			            @endif
				        </a>
					    <br />
					      </div>
					        @endif   




						@elseif($info->news_type == 'evaluated_photo')<!--Alguém que você segue avaliou uma foto-->
						@if($info->secondary_type == NULL && $info->tertiary_type == NULL)
	<div class="gallery_box">
	  <a href='{{ URL::to("/evaluations") . "/" . $info->object_id . "/viewEvaluation/" . $info->sender_id}}'>                 
	      <img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
	          <img src="/img/mask-avaliacao.png" class="evaluated-mask">
		    </a>
		      <a  href='{{ URL::to("/evaluations") . "/" . $info->object_id . "/viewEvaluation/" . $info->sender_id}}' class="name">
		          @if($info->data == null)
	      {{User::find($info->sender_id)->name}} avaliou esta imagem
	            @else
		            <?php 
			              $users = explode(":", $info->data);
				                $users_size = count($users);
						        ?>
							        {{$users_size}} usuários avaliaram esta imagem
								      @endif
								        </a>
									  <br />
									  </div>
									  @else
									  <div class="gallery_box">
									    <a href='{{ URL::to("/photos") . "/" . $info->object_id }}'>                 
									        <img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
										  </a>
										    <a  href='{{ URL::to("/photos") . "/" . $info->object_id }}' class="name">
										        @if($info->data == null)
	      {{User::find($info->sender_id)->name}} realizou diversas ações nesta imagem. Confira!
	          @else
		        Diversos usuários realizaram diversas ações nesta imagem. Confira!
			    @endif
			      </a>
			        <br />
				</div>
				@endif
				@elseif($info->news_type == 'new_profile_picture')<!--Alguém que você segue trocou a foto de perfil-->
				<div class="gallery_box">
				  <a href='{{ URL::to("/users") . "/" . $info->object_id }}'>                 
				      @if(User::find($info->object_id)->photo != null)
	      <img src={{"/arquigrafia-avatars/" . $info->object_id . ".jpg"}} title="{{ User::find($info->object_id)->name }}" class="gallery_photo">
	          @else
		        <img src="{{ URL::to("/") }}/img/avatar-48.png" title="{{User::find($info->object_id)->name}}" class="gallery_photo">
			    @endif
			      </a>
			        <a href='{{ URL::to("/users") . "/" . $info->object_id }}' class="name">
				    {{User::find($info->sender_id)->name}} trocou sua foto de perfil
				      </a>
				        <br />
					</div>
					@elseif($info->news_type == 'edited_photo')<!--Alguém que você segue editou uma foto-->
					
					@if(Photo::find($info->object_id)->type != "video")
						<div class="gallery_box">
					  		<a href='{{ URL::to("/photos") . "/" . $info->object_id }}'>                 
					      			<img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
					        	</a>
						 	 <a href='{{ URL::to("/photos") . "/" . $info->object_id }}' class="name">
						      		{{User::find($info->sender_id)->name}} editou esta imagem
						        </a>
							<br />
						 </div>
					@endif
							  @elseif($info->news_type == 'edited_profile')<!--Alguém que você segue editou o perfil-->
							  <div class="gallery_box">
							    <a href='{{ URL::to("/users") . "/" . $info->object_id }}'>                 
							        @if(User::find($info->object_id)->photo != null)
	      <img src={{"/arquigrafia-avatars/" . $info->object_id . ".jpg"}} title="{{ User::find($info->object_id)->name }}" class="gallery_photo">
	          @else
		        <img src="{{ URL::to("/") }}/img/avatar-48.png" title="{{User::find($info->object_id)->name}}" class="gallery_photo">
			    @endif
			      </a>
			        <a href='{{ URL::to("/users") . "/" . $info->object_id }}' class="name">
				    {{User::find($info->sender_id)->name}} editou seu perfil
				      </a>
				        <br />
					</div>
					@elseif($info->news_type == 'highlight_of_the_week')<!--Destaque da semana-->
					<div class="gallery_box">
					  <a href='{{ URL::to("/photos") . "/" . $info->object_id }}'>                 
					      <img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
					        </a>
						  <a href='{{ URL::to("/photos") . "/" . $info->object_id }}' class="name">
						      Confira o destaque desta semana!
						        </a>
							  <br />
							  </div>
							  @elseif($info->news_type == 'liked_photo')<!--Alguém que você segue gostou de uma foto-->
							  @if($info->secondary_type == NULL && $info->tertiary_type == NULL)
	<div class="gallery_box">
	  <a href='{{ URL::to("/photos") . "/" . $info->object_id }}'>                 
	      <img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
	        </a>
		  <a href='{{ URL::to("/photos") . "/" . $info->object_id }}' class="name">
		      @if($info->data == null)
	      {{User::find($info->sender_id)->name}} gostou desta imagem
	          @else
		        <?php 
			        $users = explode(":", $info->data);
				        $users_size = count($users);
					      ?>
					            {{$users_size}} usuários gostaram desta imagem
						        @endif
							  </a>
							    <br />
							    </div>
							    @else
							    <div class="gallery_box">
							      <a href='{{ URL::to("/photos") . "/" . $info->object_id }}'>                 
							          <img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
								    </a>
								      <a  href='{{ URL::to("/photos") . "/" . $info->object_id }}' class="name">
								          @if($info->data == null)
	      {{User::find($info->sender_id)->name}} realizou diversas ações nesta imagem. Confira!
	          @else
		        Diversos usuários realizaram diversas ações nesta imagem. Confira!
			    @endif  
			      </a>
			        <br />
				</div>
				@endif
				@elseif($info->news_type == 'check_evaluation')
				<div class="gallery_box">
				  <a href='{{ URL::to("/evaluations") . "/" . $info->object_id . "/evaluate" }}'>                 
				     <img src={{"/arquigrafia-images/" . $info->object_id . "_home.jpg"}} title="{{ Photo::find($info->object_id)->name }}" class="gallery_photo" />
				         <img src="/img/mask-avaliacao.png" class="evaluated-mask">
					   </a>
					     <a href='{{ URL::to("/evaluations") . "/" . $info->object_id . "/evaluate" }}' class="name">
					        Confira as últimas impressões sobre a imagem {{Photo::find($info->object_id)->name}} 
						  </a>
						    <br />
						    </div>
						    @elseif($info->news_type == 'check_leaderboard')
						    <div class="gallery_box">
						      <a href='{{ URL::to("/leaderboard") }}'>
						          <?php 
							        $top_user = DB::table('leaderboards')->where('type', '=', 'uploads')->orderBy('count', 'desc')->first();
								      $uploader = User::find($top_user->user_id);
								          ?>
									      @if($uploader->photo != null)
	      <img src={{$uploader->photo}} title="Learderboard arquigrafia" class="gallery_photo" />
	          @else
		        <img src="{{ URL::to("/") }}/img/avatar-48.png" title="Learderboard arquigrafia" class="gallery_photo" />
			    @endif
			      </a>
			        <a href='{{ URL::to("/leaderboard") }}' class="name">
				    Conheça o quadro de colaboradores do Arquigrafia
				      </a>
				        <br />
					</div>
					@endif

					@endforeach
