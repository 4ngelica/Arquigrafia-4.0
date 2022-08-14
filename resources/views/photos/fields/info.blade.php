@if ( $field == 'description' )
  <h4>Descrição:</h4>
  <p>{{ $photo->description }}</p>
@endif
@if ( $field == 'imageAuthor' )
  <h4>Autor da Imagem:</h4>
  <p>
    <a href="{{ URL::to("/search?q=".$photo->imageAuthor)}}">
      {{ $photo->imageAuthor }}
    </a>
  </p>
@endif
@if ( $field == 'dataCriacao' )
  <h4>Data da Imagem:</h4>
  <p>
    <a href="{{ URL::to("/search?q=".$photo->dataCriacao."&t=img") }}">
      {{ Photo::translate($photo->dataCriacao) }}
    </a>
  </p>
@endif
@if ( $field == 'workAuthor' )
  <h4>Autor da Obra:</h4>
  <p>
    <a href="{{ URL::to("/search?q=".$photo->workAuthor) }}">
      {{ $photo->workAuthor }}
    </a>
  </p>
@endif
@if ( $field == 'workdate' )
  <h4>Data da Obra:</h4>
  <p>
    <a href="{{ URL::to("/search?q=".$photo->workdate."&t=work") }}">
      {{ Photo::translate($photo->workdate) }}
    </a>
  </p>
@endif
@if ( in_array($field, ['street', 'district', 'city', 'state', 'country']) )
  <h4>Endereço:</h4>
  <p>
    @if (!empty($photo->street) && !empty($photo->city))
      <a href="{{ URL::to("/search?q=".$photo->street."&city=".$photo->city) }}">
        {{ $photo->street }},
      </a>
    @elseif (!empty($photo->street))
      <a href="{{ URL::to("/search?q=".$photo->street) }}">
        {{ $photo->street }}
      </a>
      <br />
    @endif

    @if (!empty($photo->city))
      <a href="{{ URL::to("/search?q=".$photo->city) }}">
        {{ $photo->city }}
      </a>
      <br />
    @endif

    @if (!empty($photo->state) && !empty($photo->country))
      <a href="{{ URL::to("/search?q=".$photo->state) }}">{{ $photo->state }}</a> - {{ $photo->country }}
    @elseif (!empty($photo->state))
      <a href="{{ URL::to("/search?q=".$photo->state) }}">{{ $photo->state }}</a>
    @else
      {{ $photo->country }}
    @endif
  </p>
@endif