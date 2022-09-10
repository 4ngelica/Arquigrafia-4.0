@foreach ($suggestions as $suggestion)
  <tr id="suggestion_{{ $suggestion['suggestion']->id }}" class="suggestion">
    <!-- Image -->
    <td>
      <a href="/photos/{{ $suggestion['suggestion']->photo->id }}" target="_blank">
        <img class="suggestion_photo" src="/arquigrafia-images/{{ $suggestion['suggestion']->photo->id }}_home.jpg" />
      </a>
    </td>
    <!-- Photo Name -->
    <td>{{ $suggestion['suggestion']->photo->name }}</td>
    <!-- Field -->
    <td>{{ $suggestion['field_name'] }}</td>
    <!-- Current Data -->
    @if ($suggestion['field_content'] && gettype($suggestion['field_content']) == 'array')
      <td class="table-suggestion-collumn">
        @foreach ($suggestion['field_content'] as $contentItem)
          {{ $contentItem }}@if ($contentItem != end($suggestion['field_content']));@endif
        @endforeach
      </td>
    @elseif ($suggestion['field_content'])
      <td class="table-suggestion-collumn">
        {{ $suggestion['field_content'] }}
      </td>
    @else
      <td>-----</td>
    @endif
    <!-- Suggested data -->
    @if ($suggestion['suggestion']->text)
      <td class="table-suggestion-collumn">{{ $suggestion['suggestion']->text }}</td>
    @else
      <td>-----</td>
    @endif
    <td>
      <div class="new-message">
        <a target="_blank" href="/users/{{$suggestion['suggestion']->user['id']}}">
          {{ $suggestion['suggestion']->user['name'] }}
        </a>
      </div>
    </td>
    <td>
      <div class="suggestion-button thumbs-up thumbs-link" data-id="{{ $suggestion['suggestion']->id }}">
        <!-- Form for THUMBS UP -->
        <form id="send-thumbs-up-form" method="post">
          @csrf
          <input name="suggestion_id" value="{{ $suggestion['suggestion']->id }}" type="hidden"/>
          <input name="operation" value="accepted" type="hidden"/>
          <span>Aceitar</span>
        </form>
      </div>
      <div class="suggestion-button thumbs-down thumbs-link" data-id="{{ $suggestion['suggestion']->id }}">
        <!-- Form for THUMBS DOWN -->
        <form id="send-thumbs-down-form" method="post">
          @csrf
          <input name="suggestion_id" value="{{ $suggestion['suggestion']->id }}" type="hidden"/>
          <input name="operation" value="rejected" type="hidden"/>
          <span>Rejeitar</span>
        </form>
      </div>
    </td>
  </tr>
@endforeach
