@extends('admin.layouts.master')

@section('content')
    <div class="border shadow-md mx-auto mt-8 w-1/2 rounded-lg p-6">
        <h1 class="font-semibold text-xl uppercase mb-8"> Admin Login </h1>
        <form action="{{ route('admin.login') }}" method="POST" class="">
            {{ csrf_field() }}
            <div class="mb-4">
                <label for="username" class="control mb-2"> Username </label>
                <input type="text" 
                    name="username" 
                    class="control is-lg" 
                    placeholder="username">
            </div>
            <div class="mb-4">
                <label for="password" class="control mb-2"> Password </label>
                <input type="password" name="password" class="control is-lg">
            </div>
            <div class="mb-4">
                <label for="remmember" class="control flex items-center cursor-pointer"> 
                    Remmember Me? <input type="checkbox" name="remmember" id="remmember" class="ml-2">
                </label>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-blue-dark text-sm font-bold text-white px-4 py-2 uppercase">Login</button>
            </div>
        </form>
    </div>
@endsection
