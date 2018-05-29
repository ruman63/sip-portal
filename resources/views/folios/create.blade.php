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
    <folio-entry-form route="{{ route('folios.store') }}" inline-template>
        <form class="py-3">
            {{ csrf_field() }}
            <div class="flex flex-wrap mb-2">
                <div class="w-full px-1 mb-2">

                    <label for="radio-type-fresh" class="control inline-flex items-center mr-3">
                        <input type="radio" class="mr-1" v-model="form.type" value="fresh" id="radio-type-fresh" checked>
                        Fresh
                    </label>
                    <label for="radio-type-existing" class="control inline-flex items-center">
                        <input type="radio" class="mr-1" v-model="form.type" value="existing" id="radio-type-existing">
                        Existing
                    </label>
                </div>
                <div class="field w-1/2 px-1">
                    <label for="folio_no" class="control">Folio Number</label>
                    <input v-if="isFresh" type="text" v-model="form.folio" id="folio_no" class="control" required>
                    <select v-else v-model="form.folio" @change="changeFolio" id="folio_no" class="control">
                        <option value="" disabled selected>Select A Folio</option>
                        <option v-for="folio in folios" 
                            :value="folio.folio_no" 
                            v-text="folio.folio_no">
                        </option>
                    </select>
                    <span class="text-red text-xs mt-1">{{ $errors->first('folio_no') }}</span>
                </div>
                <div class="field w-1/2 px-1">
                    <label for="scheme_code" class="control">Scheme Code</label>
                    <v-typeahead class="w-full" 
                        v-if="isFresh"
                        v-model="form.scheme" 
                        url="{{ route('schemes.index') }}">
                    </v-typeahead>
                    <div v-else class="text-xs" v-text="`[${form.scheme.scheme_code}] ${form.scheme.scheme_name}`"></div>
                    {{-- <input type="text" name="scheme_code" placeholder="e.g. LT-17" id="scheme_code" class="control"
                        required> --}}
                    <span class="text-red text-xs mt-1">{{ $errors->first('scheme_code') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="folio_no" class="control">Transaction ID</label>
                    <input type="text" name="transaction_uid" id="transaction_uid" class="control" value="{{ old('transaction_uid') }}" required>
                    <input type="hidden" name="type" value="ADD">
                    <span class="text-red text-xs mt-1">{{ $errors->first('transaction_uid') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="date" class="control">Date of Trade</label>
                    <input type="date" name="date" id="date" class="control" required value="{{ old('date') }}">
                    <span class="text-red text-xs mt-1">{{ $errors->first('date') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="rate" class="control">Purchase Price</label>
                    <input type="number" placeholder="0.00" name="rate" id="rate" class="control" value="{{ old('rate') }}" required>
                    <span class="text-red text-xs mt-1">{{ $errors->first('rate') }}</span>
                </div>
                <div class="field w-1/4 px-1">
                    <label for="amount" class="control">Amount</label>
                    <input type="number" placeholder="0.00" name="amount" id="amount" class="control" value="{{ old('amount') }}" required>
                    <span class="text-red text-xs mt-1">{{ $errors->first('amount') }}</span>
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="btn is-blue uppercase text-sm mr-2">Add</button>
                <button type="reset" class="btn uppercase text-sm">Clear</button>
            </div>
        </form>
    </folio-entry-form>
</section>

<section class="py-4">
    <h2 class="mb-4">Your Folio</h2>
    <v-data-table :names="['folio_no', 'scheme.scheme_name', 'scheme.scheme_type', 'totalUnits', 'averageRate', 'totalAmount']"
        :labels="['Folio', 'Scheme', 'Scheme Type', 'Units', 'Average Rate', 'Amount']" url="{{ route('folios.index') }}"></v-data-table>
</section>
@endsection