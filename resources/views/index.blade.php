@extends('layouts.master')
@section('content')
<section class="bg-grey-light">
    <div class="container mx-auto flex flex-col h-screen items-center justify-center">
        <h1 class="text-2xl uppercase">
            Welcome to SIP Wealth
        </h1>
        <ul class="flex list-reset mt-4">
            <li class="flex mr-4">
                <a href="#" class="py-3 px-3 text-white bg-teal-dark hover:bg-teal-darker" @click="$modal.show('register')">Register</a>
            </li>
            <li class="flex">
                <a href="#" class="py-3 px-3 text-white bg-teal-dark hover:bg-teal-darker" @click="$modal.show('login')">Login</a>
            </li>
        </ul>
    </div>
</section>
    @include('modals.register')
    @include('modals.login')
@endsection