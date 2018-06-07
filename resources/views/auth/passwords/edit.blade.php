@extends('layouts.master')
@section('content')
    <section class="py-4 md:py-8 px-2 md:px-0" >
        <div class="mx-auto w-full md:w-2/3 lg:w-1/2 bg-white border rounded shadow lg:rounded-lg p-2 sm:p-6 lg:p-8">
            <h2 class="mb-6">Change Password</h2>
            <form action="{{ route('password.update') }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('patch') }}
                <div class="field{{ $errors->has('current_password') ? ' has-error' : '' }}">
                    <label class="control" for="">Current Password</label>
                    <input type="password" name="current_password" class="control">
                    <span class="mt-1 text-xs text-red"> {{ $errors->first('current_password') }} </span>
                </div>
                <div class="field{{ $errors->has('new_password') ? ' has-error' : '' }}">
                    <label class="control" for="">New Password</label>
                    <input type="password" name="new_password" class="control">
                    <span class="mt-1 text-xs text-red"> {{ $errors->first('new_password') }} </span>
                </div>
                <div class="field{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                    <label class="control" for="">Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="control">
                </div>
                <div class="flex justify-end">
                    <button class="btn mr-3" type="reset">Clear</button>
                    <button class="btn is-blue" type="submit">Change Password</button>
                </div>
            </form>
        </div>
    </section>
@endsection