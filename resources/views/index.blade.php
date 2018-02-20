@extends('layouts.app')
@section('content')
    <div>
        {{ Auth::guest() ? 'guest' : Auth::user()->first_name }}
    </div>
    
    
    @include('modals.register')
    @include('modals.login')
@endsection