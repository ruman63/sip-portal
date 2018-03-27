@extends('layouts.master')
@section('content')
    <ul class="breadcrumbs mb-6">
        <li><a href="{{ route('dashboard') }}"> Home </a></li>
        <li> Allocations </li>
    </ul>
    <section class="py-4">
        <h2 class="mb-4"> Fund Allocation Stats </h2>
        <div class="flex">
            <div class="w-1/3 mr-2">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Scheme Type</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td>{{ $asset->type }}</td>
                                <td class="text-right"> {{ currency($asset->amount) }} &#x20B9; </td>
                                <td class="text-right">{{ number_format($asset->percent, 2) }}%</td>
                            </tr>
                        @endforeach
                        <tr class="border-t-2 font-semibold">
                            <td>Total</td>
                            <td class="text-right"> {{ currency($assets->sum('amount')) }} &#x20B9; </td>
                            <td class="text-right">{{ number_format($assets->sum('percent'), 2) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex-1 flex justify-center items-center">
                <chart class="w-full" 
                    type="pie" 
                    :values="{{ $assets->pluck('amount')->toJson() }}"
                    :labels="{{ $assets->pluck('type')->toJson() }}">
                </chart>
            </div>
        </div>
        
    </section>
@endsection