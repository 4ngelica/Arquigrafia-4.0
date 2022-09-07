<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Arquigrafia') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
      @include('new_front.nav')
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container py-5">
                <div class="flex-row w-100 d-flex justify-content-around">
                  <div class="p-2"><img src="{{asset('/img/logo.chou.arquigrafia.png')}}" alt="" width="223" height="33"></div>
                  <div class="p-2"><form class="" action="index.html" method="post">
                    <input type="text" name="" value="busca">
                  </form></div>
                  <div class="p-2">Login</div>


                </div>
            </div>
        </nav> -->
        <main class="py-4">
            @yield('content')
        </main>
        @include('new_front.footer')
    </div>
</body>
</html>
