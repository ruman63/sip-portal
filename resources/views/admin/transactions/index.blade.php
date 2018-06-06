@extends('admin.layouts.master') 
@section('content')
<section class="py-2">
    <ul class="breadcrumbs">
        <li><a href="{{ route('admin.dashboard') }}"> Home </a></li>
        <li> Transactions </li>
    </ul>
</section>
<transactions-page v-cloak></transactions-page>
@endsection