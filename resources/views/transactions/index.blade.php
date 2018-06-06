@extends('layouts.master') 
@section('content')
    <section class="py-2">
        <ul class="breadcrumbs">
            <li><a href="{{ route('dashboard') }}"> Home </a></li>
            <li> Transactions </li>
        </ul>
    </section>
    <section class="py-4">
        <header class="flex justify-between items-baseline mb-6 pb-1 border-b-2">
            <h1>Your Transactions</h1>
        </header>
        <v-data-table
            class="mb-8"
            :data="{{ $transactions->toJson() }}"
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
                </tr>
            </template>
        </v-data-table>
    </section>
@endsection