<?php $count = 0; ?>
<table id="{{ $type . '_page' . $page }}" class="page form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
@foreach($photos as $photo)
	@if ($count % 6 == 0)
		<tr>
	@endif
	<td width="143">
		<input type="checkbox" class="{{ $type . '_photo'}}" id="{{ 'photo_' . $photo->id }}" name="{{ 'photos_' . $type . '[]' }}" value="{{ $photo->id }}">
		<label id="{{ 'label_' . $photo->id }}" for="{{ 'photo_' . $photo->id }}"></label>
	</td>
	@if ($count % 6 == 5)
		</tr>
	@endif
	<?php $count++ ?>
@endforeach
	@if($count % 6 != 0)
		@while($count % 6 != 0)
			<td width="143"></td>
			<?php $count++; ?>
		@endwhile
		</tr>
	@endif
</table>
<style>
	@foreach($photos as $photo)
		{{ '#photo_' . $photo->id . ' + label' }}
		{
			background: url('{{"/arquigrafia-images/" . $photo->id . "_home.jpg" }}');
		}
	@endforeach
</style>
