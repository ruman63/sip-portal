<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
</head>
<body class="font-sans">
    <div id="app">
        
        @include('layouts.nav')
        
        <main class="py-4">
            @yield('content')
        </main>

    </div>

    <!-- Scripts -->
</body>
</html>
