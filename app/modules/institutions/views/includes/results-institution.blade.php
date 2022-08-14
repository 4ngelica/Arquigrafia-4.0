<div class="container">
<div id="user_header" class="twelve columns">
  <div class="div_avatar_size_inst" >
            <img class ="class_img_avatar" class="avatar" src="{{ asset($institution->photo) }}"
              class="user_photo_thumbnail"/>
  </div> 
  <div class="info">
    <h1>Imagens do {{$institution->name}}</h1>
    <p><a href="{{ URL::to('/institutions/'.$institution->id) }}">Ir ao Perfil</a></p>
  </div>  
</div>  

      <div class="twelveMid columns">    
       <div id="add_images" class="" style="display: block;"> 
        <div id="add" class="twelveMid columns add" >          
          @if ( $photos!= null)
              @if ($photos->count() > 0)
                
                 @include('includes.all_images')
                 
              @else
                <p>Não foi encontrada nenhuma imagem para esta instituição.</p>
              @endif
           @else
               <div class="wrap">
               </div>
           @endif   
        </div>
        @if ( $photos!= null)
        <div class="eleven columns block add">
           <div class="eight columns alpha buttons">
            {{ $photos->links()}}
            </div>          
        </div>
        @endif
        </div>
        
      </div>
</div>




