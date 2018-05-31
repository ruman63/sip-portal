@extends('layouts.master') 
@section('content')
    <section class="py-2">
        <ul class="breadcrumbs">
            <li><a href="{{ route('dashboard') }}"> Home </a></li>
            <li> Manual Entry </li>
        </ul>
    </section>
    <section class="py-4">
        <h2 class="mb-6">Transactions Entry</h2>
        <folio-entry-form route="{{ route('transactions.store') }}" inline-template>
            <div>
                <form class="py-3" @submit.prevent="submit">
                    {{ csrf_field() }}
                    <div class="flex flex-wrap mb-2">
                        <div class="w-full px-1 mb-2">
                            <label for="radio-type-fresh" class="control inline-flex items-center mr-3">
                                <input type="radio" class="mr-1" v-model="type" value="fresh" id="radio-type-fresh" checked>
                                New Folio
                            </label>
                            <label for="radio-type-existing" class="control inline-flex items-center">
                                <input type="radio" class="mr-1" v-model="type" value="existing" id="radio-type-existing">
                                Existing Folio
                            </label>
                        </div>
                        <div class="field w-1/2 px-1">
                            <label for="folio_no" class="control">Folio Number</label>
                            <input v-if="isFresh" type="text" v-model="form.folio_no" id="folio_no" class="control" required>
                            <select v-else v-model="form.folio" @change="changeFolio" id="folio_no" class="control">
                                <option value="" disabled selected>Select A Folio</option>
                                <option v-for="folio in folios" 
                                    :value="folio" 
                                    v-text="folio">
                                </option>
                            </select>
                            <span class="text-red text-xs mt-1">{{ $errors->first('folio_no') }}</span>
                        </div>
                        <div class="field w-1/2 px-1">
                            <label for="scheme_code" class="control">Scheme Code</label>
                            <v-typeahead class="w-full" 
                                v-model="form.scheme_code" 
                                url="{{ route('schemes.index') }}">
                            </v-typeahead>
                            {{-- <input type="text" name="scheme_code" placeholder="e.g. LT-17" id="scheme_code" class="control"
                                required> --}}
                            <span class="text-red text-xs mt-1">{{ $errors->first('scheme_code') }}</span>
                        </div>
                        <div class="field w-1/4 px-1">
                            <label for="folio_no" class="control">Transaction ID</label>
                            <input type="text" v-model="form.transaction_uid" id="transaction_uid" class="control" required>
                            <span class="text-red text-xs mt-1">{{ $errors->first('transaction_uid') }}</span>
                        </div>
                        <div class="field w-1/4 px-1">
                            <label for="date" class="control">Date of Trade</label>
                            <input type="date" v-model="form.date" id="date" class="control" required>
                            <span class="text-red text-xs mt-1">{{ $errors->first('date') }}</span>
                        </div>
                        <div class="field w-1/4 px-1">
                            <label for="rate" class="control">Purchase Price</label>
                            <input type="number" placeholder="0.00" id="rate" class="control" v-model="form.rate" required>
                            <span class="text-red text-xs mt-1">{{ $errors->first('rate') }}</span>
                        </div>
                        <div class="field w-1/4 px-1">
                            <label for="amount" class="control">Amount</label>
                            <input type="number" placeholder="0.00" id="amount" class="control" v-model="form.amount" required>
                            <span class="text-red text-xs mt-1">{{ $errors->first('amount') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="btn is-blue uppercase text-sm mr-2">Add</button>
                        <button type="reset" class="btn uppercase text-sm">Clear</button>
                    </div>
                </form>
                <div class="py-4">
                    <h2 class="mb-4">Your Transactions</h2>
                    <v-data-table
                        class="mb-8"
                        :data="transactions"
                        :names="['date', 'uid', 'folio_no', 'scheme.scheme_name', 'scheme.scheme_type', 'units', 'rate', 'amount']"
                        :labels="['Date', 'Txn ID', 'Folio', 'Scheme', 'Scheme Type', 'Units', 'Rate', 'Amount']"
                    >
                        <template slot-scope="{ item }">
                            <td>@{{ item.date }}</td>
                            <td>@{{ item.uid }}</td>
                            <td>@{{ item.folio_no }}</td>
                            <td>@{{ item.scheme.scheme_name }}</td>
                            <td>@{{ item.scheme.scheme_type }}</td>
                            <td>@{{ item.units }}</td>
                            <td>@{{ item.rate }}</td>
                            <td>@{{ item.amount | currency }} &#x20B9;</td>
                        </template>
                    </v-data-table>
                </div>
            </div>
        </folio-entry-form>
    </section>

    
@endsection