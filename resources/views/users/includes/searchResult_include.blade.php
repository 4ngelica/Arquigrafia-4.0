<?php $count = 0;
	  $type = 'add';
?>

<table class="page form-table users-result-table" width="100%" border="0"
	cellspacing="0" cellpadding="0">
  @foreach($users as $user)
		@if ($count % 8 == 0)
		<tr>
		@endif
		<td width="110">
      <div style="width: 100%; text-align: center;">
        <a href='{{ URL::to("/users/{$user->id}") }}'>
          @if ( !empty($user->photo) )
            <div class="img_avatar_result" style="background: url({{ asset($user->photo) }}) center center no-repeat; background-size: cover;"></div>
          @else
            <div class="img_avatar_result" style="background: url({{ URL::to('/img/avatar-60.png') }}) center center no-repeat; background-size: cover;"></div>
          @endif
        </a>
      </div>
      <div style="width: 100%; text-align: center;">
        <span>{{ $user-> name }}</span>
      </div>
    </td>
  	@if ($count % 8 == 7)
  	</tr>
  	@endif
	<?php $count++ ?>
  @endforeach
  @if($count % 8 != 0)
  	@while($count % 8 != 0)
  		<td></td>
  		<?php $count++; ?>
  	@endwhile
  	</tr>
  @endif
</table>
