<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->

    <title>Arquigrafia - Seu universo de imagens de arquitetura</title>

    <!-- Scripts -->



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet"></script>
</head>
<body>
    <div id="app">
      @include('new_front.layouts.nav')
        <main class="py-4">
            @yield('content')
        </main>
        @include('new_front.layouts.footer')
    </div>
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
