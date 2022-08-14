@foreach ($drafts as $draft)
  <tr id="draft_{{ $draft->id }}" class="draft">
    <td>{{ $draft->tombo }}</td>
    <td>{{ $draft->support }}</td>
    <td>{{ $draft->name }}</td>
    <td><a href="{{ URL::to('/drafts/' . $draft->id) }}">Completar</a></td>
    <td><a href="#" data-draft="{{ $draft->id }}" class="delete_draft">Excluir</a></td>
  </tr>
@endforeach
