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
    <style>
        .v--modal-overlay .v--modal-box {
            overflow: initial !important;
        }
    </style>
</head>
<body class="font-sans">
    <div id="app" class="flex flex-col h-screen">
        {{--  Todo: navbar here  --}}
        @include('admin.layouts.nav')
        
        <div class="flex flex-1 overflow-hidden">
            
            @if(Auth::guard('cpanel')->check())
                @include('admin.layouts.side-menu')
            @endif
            
            <main class="py-4 px-6 flex-1 h-full w-full overflow-y-scroll">
                @yield('content')
            </main>
        </div>
        
        @include('flash::message')
        @include('modals.index')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
