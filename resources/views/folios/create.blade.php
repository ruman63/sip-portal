@extends('layouts.master')
@section('content')
    <section class="py-2">
        <ul class="breadcrumbs">
            <li><a href="{{ route('dashboard') }}"> Home </a></li>
            <li> Folio Entry </li>
        </ul>
    </section>
    <section class="py-4">
        <h2 class="mb-6">Create Folio Entry</h2>
        <form method="POST" action="{{ route('folios.store') }}" class="py-3">
            {{ csrf_field() }}
            
            <div class="flex flex-wrap mb-2">
                <div class="field w-full px-1">
                    <label for="scheme_code" class="control">Scheme Code</label>
                    <input type="text" name="scheme_code" placeholder="e.g. LT-17" id="scheme_code" class="control" required>
                    <span class="text-red text-xs mt-1">{{ $errors->first('scheme_code') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="folio_no" class="control">Folio Number</label>
                    <input type="text" name="folio_no" id="folio_no" class="control" value="{{ old('folio_no') }}" required>
                    <span class="text-red text-xs mt-1">{{ $errors->first('folio_no') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="trade_date" class="control">Date of Trade</label>
                    <input type="date" name="trade_date" id="trade_date" class="control" required>
                    <span class="text-red text-xs mt-1">{{ $errors->first('trade_date') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="purchase_price" class="control">Purchase Price</label>
                    <input type="number" placeholder="0.00" name="purchase_price" id="purchase_price" class="control" required>
                    <span class="text-red text-xs mt-1">{{ $errors->first('purchase_price') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="amount" class="control">Amount</label>
                    <input type="number" placeholder="0.00" name="amount" id="amount" class="control" required>
                    <span class="text-red text-xs mt-1">{{ $errors->first('amount') }}</span>
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="btn is-blue uppercase text-sm mr-2" >Add</button>
                <button type="reset" class="btn uppercase text-sm">Clear</button>
            </div>
        </form>
    </section>

    <section class="py-4">
        <h2 class="mb-4">Your Folio</h2>
        <v-data-table
            :names="['folio_no', 'client_id', 'scheme_code', 'purchase_price', 'amount']"
            url="{{ route('folios.index') }}"
        ></v-data-table>
    </section>
@endsection