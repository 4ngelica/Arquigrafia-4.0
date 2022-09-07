@extends('new_front.app')

@section('content')
<!-- <div class="grid">
  <div v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="20">
    <div class="image-grid">

      @foreach($photos as $photo)
      <div class="image-item">
          <img src="{{ asset('/arquigrafia-images/'. $photo->id . '_view.jpg') }}" >
      </div>
      @endforeach
    </div>
  </div>
</div> -->
  <example-component></example-component>
@endsection
