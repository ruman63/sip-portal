@extends('layouts.master')
@section('content')
    <ul class="breadcrumbs mb-6">
        <li><a href="{{ route('dashboard') }}"> Home </a></li>
        <li> Portfolio Summary </li>
    </ul>
    <section class="py-4">
        <h2 class="mb-6"> Portfolio Summary </h2>
        <div class="flex">
            <table class="w-full text-xs">
                <thead>
                    <tr>
                        <th>Scheme</th>
                        <th>Folio</th>
                        <th>Dividend <br> Reinvest</th>
                        <th>Sell</th>
                        <th>Units</th>
                        <th class="text-right">Purchase <br> Value</th>
                        <th class="text-right">Current <br> Value</th>
                        <th class="text-right">Gain</th>
                        <th>Absolute <br> Return</th>
                        <th>XIRR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($portfolio as $folio)
                        <tr>
                            <td title="{{ $folio->scheme->scheme_name }}">
                                {{ str_limit($folio->scheme->scheme_name, 40) }} 
                            </td>
                            <td>
                                {{ $folio->folio_no }} 
                            </td>
                            <td>
                                {{ $folio->reinvest }} 
                            </td>
                            <td>
                                {{ $folio->sell }} 
                            </td>
                            <td>
                                {{ round($folio->totalUnits, 2) }}
                            </td>
                            <td class="text-right">
                                {{ round($folio->totalAmount, 2) }} &#x20B9;
                            </td>
                            <td class="text-right">
                                {{ round($folio->currentValue, 2) }} &#x20B9;
                            </td>
                            <td class="text-right">
                                {{ round($folio->currentValue - $folio->totalAmount, 2) }} &#x20B9;
                            </td>
                            <td>
                                {{ $folio->absoluteReturn }}
                            </td>
                            <td>
                                {{ $folio->xirr }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection