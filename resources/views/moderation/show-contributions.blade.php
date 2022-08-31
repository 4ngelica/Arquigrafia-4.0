@extends ('layouts.default')

@section ('head')
  <title>Arquigrafia - Contribuições</title>
  <link rel="stylesheet" type="text/css" href="{{ URL::to('/css/tabs.css') }}">
  <!-- Setting global variables that comes from server side -->
  <script>
    var currentUser = {{ json_encode($currentUser) }};
    var isGamefied = {{ json_encode($isGamefied) }};
    var selectedTab = {{ json_encode($selectedTab) }};
    var selectedFilterId = {{ json_encode($selectedFilterId) }};
  </script>
  <!-- LOADING VUE.JS BUNDLE -->
  <script src="/js/dist/contributions.bundle.js"></script>
@stop

@section ('content')
  <div class="container">
    <div id="contributions-content">
      <!-- HERE, VUE.JS WILL RENDER CONTENT - CONTRIBUTIONS -->
    </div>
  </div>
@stop
