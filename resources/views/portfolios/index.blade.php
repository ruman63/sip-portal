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
                        {{-- <th> </th> --}}
                        {{-- <th>Sell</th> --}}
                        <th class="text-right">Units</th>
                        <th class="text-right"> Rate </th>
                        <th class="text-right"> Purchase Value </th>
                        <th class="text-right"> Current <br> Nav </th>
                        <th class="text-right"> Current <br> Value </th>
                        <th class="text-right"> Gain </th>
                        <th class="text-right"> Absolute <br> Return </th>
                        <th class="text-right"> XIRR </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($portfolio as $type => $transactions)
                        <tr>
                            <td colspan="10" class="text-blue-dark font-semibold">
                                <strong>{{ $type }}</strong>
                            </td>
                        </tr>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td title="{{ $transaction->scheme->scheme_name }}">
                                    {{ str_limit($transaction->scheme->scheme_name, 40) }} 
                                </td>
                                <td>
                                    {{ $transaction->folio_no }} 
                                </td>
                                <td class="text-right">
                                    {{ currency(round($transaction->units, 2)) }}
                                </td>
                                <td class="text-right">
                                    {{ round($transaction->rate, 2) }} &#x20B9;
                                </td>
                                <td class="text-right">
                                    {{ currency(round($transaction->amount, 2)) }} &#x20B9;
                                </td>
                                <td class="text-right">
                                    {{ currency($transaction->scheme->nav) }} &#x20B9;
                                </td>
                                <td class="text-right">
                                    {{ currency(round($currentValue = ($transaction->scheme->nav * $transaction->units), 2)) }} &#x20B9;
                                </td>
                                <td class="text-right {{ $currentValue < $transaction->amount ? 'text-red' : 'text-green-dark' }}">
                                    {{ currency(round($gain = ($currentValue - $transaction->amount), 2)) }} &#x20B9;
                                </td>
                                <td class="text-right {{ $gain < 0 ? 'text-red' : 'text-green-dark' }}">
                                    {{ round($absoluteReturn = ($gain*100/$transaction->amount), 2) }} %
                                </td>
                                <td class="text-right {{ $gain < 0 ? 'text-red' : 'text-green-dark' }}">
                                        {{ round($xirr = $absoluteReturn/(\Carbon\Carbon::parse($transaction->date)->diffInDays()) * 365, 2) }} %
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr class="border-t-2 text-sm font-semibold uppercase">
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">
                            {{ currency($purchase = round(
                                    $portfolio->sum(function($txns) {
                                        return $txns->sum('amount');
                                    }), 2
                                )) 
                            }} &#x20B9;
                        </td>
                        <td></td>
                        <td class="text-right">
                            {{ currency($current = round(
                                    $portfolio->sum(function($txns) {
                                        return $txns->sum(function($txn) {
                                            return $txn->scheme->nav * $txn->units;
                                        });
                                    }), 2
                                )) 
                            }} &#x20B9;
                        </td>
                        <td class="text-right {{ $current < $purchase ? 'text-red' : 'text-green-dark' }}">
                            {{ $loss = currency($current - $purchase) }} &#x20B9;
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection