<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="overflow-hidden">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .v--modal-overlay .v--modal-box {
            overflow: initial !important;
        }
    </style>
</head>
<body class="font-sans">
    <div id="app" class="flex flex-col h-screen">
        
        @include('layouts.nav')
        
        <main class="flex-1 w-full overflow-auto">
            <div class="container h-full mx-auto">
                @yield('content')
            </div>
        </main>

        @include('layouts.footer')
        
        @include('flash::message')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
