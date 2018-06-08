@extends('admin.layouts.master') 
@section('breadcrumbs')
    <li> Transactions </li>
@endsection
@section('content')
    <transactions-page v-cloak></transactions-page>
@endsection