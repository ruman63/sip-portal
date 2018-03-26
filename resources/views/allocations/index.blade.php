@extends('layouts.master')
@section('content')
    <ul class="breadcrumbs mb-6">
        <li><a href="{{ route('dashboard') }}"> Home </a></li>
        <li> Allocations </li>
    </ul>
    <section class="py-4">
        <h2 class="mb-4"> Funds Allocations Stats </h2>
        <table>
            <thead>
                <tr>
                    <th>Scheme Type</th>
                    <th>Amount</th>
                    <th>% Share</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->type }}</td>
                        <td>{{ $asset->amount }}</td>
                        <td>{{ $asset->percent }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection