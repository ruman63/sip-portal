@extends('layouts.master') 
@section('content')
<ul class="breadcrumbs">
    <li><a href="{{ route('dashboard') }}"> Home </a></li>
    <li> Profile </li>
</ul>
<client-profile-view :data-client="{{ $client->toJson() }}"></client-profile-view>
@endsection