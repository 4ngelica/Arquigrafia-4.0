@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>
  <script type="text/javascript" src="{{ URL::to("/") }}/js/panel.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ URL::to('/css/tabs.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/album.css" />
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/checkbox-edition.css" />
  <script src="{{ URL::to("/") }}/js/jquery.isotope.min.js"></script>
  <link rel="stylesheet" type="text/css" media="screen"
    href="{{ URL::to("/") }}/css/checkbox.css" />
    <script type="text/javascript">

    $(window).on('load', function() {
       $("span:contains('«')").addClass('page-ini-span');
       $("span:contains('»')").addClass('page-end-span');

       $('a[rel="prev"]').addClass("link-ini");
       $('a[rel="next"]').addClass("link-end");

       if (document.querySelector('.page-ini-span') !== null) {
            $( ".page-ini-span" ).empty();
       }

        if (document.querySelector('.page-end-span') !== null) {
            $( ".page-end-span" ).empty();
        }

       var thumb = "<?php echo URL::to('/').'/img/btnNext.png';?>";
       var thumbIni = "<?php echo URL::to('/').'/img/btnPrev.png';?>";

      if ($('a[rel="prev"]').hasClass("link-ini")) {
           $( ".link-ini" ).empty();

           $( ".link-ini" ).append(
               $('<img>').attr('src',thumbIni)
            );
      }

      if ($('a[rel="next"]').hasClass("link-end")) {
           $( ".link-end" ).empty();
           $( ".link-end" ).append(
               $('<img>').attr({
                src:thumb,
                'vertical-align':'top'
              })
            );
      }

    });

    </script>
@stop

@section('content')
  <div id="content">
      <div id="search_result" class="twelve columns row">
        @include('institutions.results-institution')
      </div>
  </div>

@stop
