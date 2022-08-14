<?php $i = 0;?>
          
@foreach($photos as $photo)

  <?php
    $i++;
    $size = 1; 
    if ($i%5 == 3) $size = 2;
    if ($i%10 == 8) $size = 3;
  ?>
  
  <div class="item h<?php echo $size; ?>"><div class="layer" data-depth="0.2">
    <a href='{{ URL::to("/photos/{$photo->id}") }}'>
     <img src="{{ asset('/arquigrafia-images/'. $photo->id . '_view.jpg') }}" title={{ $photo->name }}>

    </a>
  </div></div>
  
@endforeach