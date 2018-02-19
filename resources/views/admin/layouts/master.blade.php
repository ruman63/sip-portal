<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> SIP Wealth | Admin Dashboard</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="font-sans">
    <div id="app">
        {{--  Todo: navbar here  --}}
        @include('admin.layouts.nav')

        <div class="container mx-auto flex min-h-screen">
            
            @if(Auth::guard('cpanel')->check())
                @include('admin.layouts.side-menu')
            @endif
            
            <main class="py-4 px-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
