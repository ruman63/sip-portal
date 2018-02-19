@extends('layouts.app')
@section('content')
    <div class="container mx-auto">
        <h3 class="mb-4 text-center"> NAV Values - Synced from AMFI </h3>
        <form action="/" method="get" class="mb-8 w-fit block mx-auto">
            <select name="category" class="p-1 bg-white focus:border-teal-dark border-grey-light border rounded">
                <option value {{ request('category') ? '' : 'selected' }}>ALL</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                @endforeach
            </select>
    
            <select name="company" class="p-1 bg-white focus:border-teal-dark border-grey-light border rounded">
                <option value {{ request('company') ? '' : 'selected' }}>ALL</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>{{$company->name}}</option>
                @endforeach
            </select>
    
            <button type="submit" class="btn is-teal">Search</button>
        </form>
        
        <div class="p-1 border-grey-light border max-h-screen overflow-y-scroll text-sm mb-6">
            <table class="table-collapse relative w-full border">
                <thead class="text-left tracking-wide uppercase font-semibold relative pin-x pin-t">
                    <th class="px-2 py-2 border-b-2">Code</th>
                    <th class="px-2 py-2 border-b-2">Name</th>
                    <th class="px-2 py-2 border-b-2">Company</th>
                    <th class="px-2 py-2 border-b-2">Type</th>
                    <th class="px-2 py-2 border-b-2">NAV</th>
                    <th class="px-2 py-2 border-b-2">Date</th>
                </thead>
                <tbody class="">
                    @foreach($schemes as $scheme)
                    <tr>
                        <td class="text-sm p-1 border-t">{{ $scheme->scode }}</td>
                        <td class="text-sm p-1 border-t">{{ $scheme->name }}</td>
                        <td class="text-sm p-1 border-t">{{ $scheme->company->name }}</td>
                        <td class="text-sm p-1 border-t">{{ $scheme->category->name }}</td>
                        <td class="text-sm p-1 border-t">{{ $scheme->net_value }}</td>
                        <td class="text-sm p-1 border-t">{{ $scheme->date->toDateString() }}</td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>
    </div>
@endsection