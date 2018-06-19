@extends('admin.layouts.master')
@section('breadcrumbs')
    <li> Clients </li>
@endsection
@section('content')
    <section class="pb-8">
        <header class="page-header">
            <h1> Manage Clients </h1>
        </header>
        <div class="flex justify-between items-center mb-8">
            <div class="w-1/2">
                <input type="text" class="control" placeholder="Search Clients...">
            </div>
            <button class="btn is-blue" @click="$modal.show('register')"> 
                <i class="fa fa-user-plus mr-1"></i> Register Client
            </button>
        </div>
        <div class="overflow-x-scroll">
            <table class="max-w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>PAN</th>
                        <th>Email</th>
                        <th>Member <br>Since</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                            <td>{{ $client->pan }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->created_at->diffForHumans() }}</td>
                            <td>
                                <form class="inline-block" 
                                target="_blank"
                                action="{{ route('clients.login-as', $client) }}"
                                method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" title="Login" class="btn is-blue">
                                        <i class="fa fa-sign-in mr-1 "></i> Login
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <td colspan="6" class="text-center">No Clients found.</td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection