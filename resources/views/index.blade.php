@extends('layouts.app')
@section('content')
    <div>
        {{ Auth::guest() ? 'guest' : Auth::user()->first_name }}
    </div>
    <li><a href="#" @click="$modal.show('client-create')">Register</a></li>
    <li><a href="#" @click="$modal.show('login')">Login</a></li>
    
    @include('modals.client-create')
    @include('modals.login')
@endsection