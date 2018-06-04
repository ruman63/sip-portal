@extends('layouts.master') 
@section('content')
    <section class="py-2">
        <ul class="breadcrumbs">
            <li><a href="{{ route('dashboard') }}"> Home </a></li>
            <li> Manual Entry </li>
        </ul>
    </section>
    <folio-entry-page inline-template v-cloak>
        <section class="py-4">
            <header class="flex justify-between items-baseline mb-6 pb-1 border-b-2">
                <h1>Your Transactions</h1>
                <button @click.prevent="$modal.show('transaction-form')" class="text-sm btn is-blue">
                    <i class="fa fa-plus mr-1"></i> New Transaction 
                </button>
            </header>
            <folio-entry-form url="{{ route('transactions.store') }}" @created="created" @updated="updated" inline-template>
                <modal name="transaction-form" height="auto" @before-open="beforeOpen">
                    <form @submit.prevent="submit" class="p-8">
                        <h3 class="mb-6" v-text="title"></h3>
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
                            <div class="field w-1/4 px-1">
                                <label for="folio_no" class="control">Folio Number</label>
                                <input v-if="isFresh" type="text" v-model="form.folio_no" id="folio_no" class="control" required>
                                <select v-else v-model="form.folio_no" id="folio_no" class="control">
                                    <option value="" disabled selected>Select A Folio</option>
                                    <option v-for="folio in folios" 
                                        :value="folio" 
                                        v-text="folio">
                                    </option>
                                </select>
                                <span class="text-red text-xs mt-1">{{ $errors->first('folio_no') }}</span>
                            </div>
                            <div class="field w-3/4 px-1">
                                <label for="scheme_code" class="control">Scheme Code</label>
                                <v-typeahead class="w-full" 
                                    v-model="selectedScheme" 
                                    url="{{ route('schemes.index') }}">
                                </v-typeahead>
                                {{-- <input type="text" name="scheme_code" placeholder="e.g. LT-17" id="scheme_code" class="control"
                                    required> --}}
                                <span class="text-red text-xs mt-1">{{ $errors->first('scheme_code') }}</span>
                            </div>
                            <div class="field w-1/4 px-1">
                                <label for="folio_no" class="control">Transaction ID</label>
                                <input type="text" v-model="form.uid" id="transaction_uid" class="control" required>
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
                        <div class="flex justify-end">
                            <button type="submit" class="btn is-blue uppercase text-sm mr-2" v-text="updating ? 'Update':'Create'"></button>
                            <button type="reset" class="btn uppercase text-sm" @click="close">Cancel</button>
                        </div>
                    </form>
                </modal>
            </folio-entry-form>
            <v-data-table
                class="mb-8"
                :data="transactions"
                :labels="['Date', 'Txn Id', 'Folio', 'Scheme', 'Scheme Type', 'Units', 'Rate', 'Amount']"
                :names="['date', 'uid', 'folio_no', 'scheme.scheme_name', 'scheme.scheme_type', 'units', 'rate', 'amount']"
            >
                <template slot="header" slot-scope="{ sortColumn, columns, ascending, sort, label }">
                    <tr>
                        <th v-for="(column,i) in columns" class="cursor-pointer text-xs" @click="sort(column)">
                            <div class="flex">
                                <span class="flex-1" v-text="label(i)"></span>
                                <i v-if="sortColumn === column" class="ml-2 fa fa-caret-down" 
                                    :class="{ascending:'fa-caret-down'}"></i>
                            </div>
                        </th>
                        <th></th>
                    </tr>
                </template>
                <template slot-scope="{ item, getKey }">
                    <tr :key="getKey(item)"
                        class="text-xs">
                        <td>@{{ item.date }}</td>
                        <td>@{{ item.uid }}</td>
                        <td>@{{ item.folio_no }}</td>
                        <td>@{{ item.scheme.scheme_name }}</td>
                        <td>@{{ item.scheme.scheme_type }}</td>
                        <td class="text-right">@{{ item.units | fixed }}</td>
                        <td class="text-right">@{{ item.rate | currency }} &#x20B9;</td>
                        <td class="text-right">@{{ item.amount | currency }} &#x20B9;</td>
                        <td class="w-32 text-right">
                            <button class="btn rounded-full w-8 h-8 p-1 is-blue" @click="edit(item)"><i class="fa fa-edit text-xs"></i></button>
                            <button class="btn rounded-full w-8 h-8 p-1 bg-red hover:bg-red-dark text-white"><i class="fa fa-trash text-xs"></i></button>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </section>
    </folio-entry-page>
@endsection