@extends('admin.layouts.master')
@section('content')

    <ul class="list-reset text-xs flex mb-6">
        <li class="px-2 py-1 text-grey-dark">
            <a href="{{ route('admin.dashboard') }}">Home</a>
        </li>
        <li class="px-1 py-1 text-grey-dark">
            <i class="fa fa-arrow-right"></i>
        </li>
        <li class="px-2 py-1 text-grey-dark"> Clients </li>
    </ul>
    <section class="px-2">
        <div class="flex border-b pb-1 mb-4">
            <h2 class="flex items-center flex-grow text-blue-darkest tracking-wide font-semibold uppercase">Manage Clients</h2>
            <button class="btn is-blue" @click="$modal.show('register')"> 
                <i class="fa fa-user-plus mr-1"></i> Register Client
            </button>
        </div>
        <div class="flex flex-row-reverse px-2">
            <form action="" method="GET" class="text-sm mb-2">
                <div class="flex items-strech text-xs">
                    <input type="text" class="flex-grow border-t border-b border-l py-2 px-2 focus:border-blue-darker" placeholder="Search Clients">
                    <button type="submit" class="px-3 bg-blue-darker text-white">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="p-3 w-full overflow-x-scroll">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Member Since</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->mobile }}</td>
                            <td>{{ $client->created_at->diffForHumans() }}</td>
                            <td>
                                <form class="inline-block" 
                                target="_blank"
                                action="{{ route('clients.login-as', $client) }}"
                                method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" title="Login" class="btn is-blue">
                                        <i class="fa fa-sign-in"></i>
                                    </button>
                                </form>
                                <button class="btn is-blue" title="Fill Details"> <i class="fa fa-pencil-square-o"></i> </button>
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