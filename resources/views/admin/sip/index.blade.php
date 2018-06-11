@extends('admin.layouts.master') 
@section('breadcrumbs')
<li> Manage SIPs </li>
@endsection
 
@section('content')
<section class="mb-8">
    <header class="page-header">
        <h1>Manage SIPs</h1>
    </header>
    <main>
        <manage-sip-page :data="{{ $sipEntries }}" inline-template>
            <section>
                <div class="flex justify-end mb-4">
                    <button class="btn is-blue" @click="$modal.show('create-sip-form')"> <i class="fa fa-plus mr-1"></i> Create SIP </button>
                </div>

                <v-data-table :data="entries" 
                    :names="['id', 'folio_no', 'client.name', 'scheme.scheme_name', 'interval', 'installments', 'date', 'amount']" 
                    :labels="['id', 'Folio', 'Client', 'Scheme', 'Frequency', '#', 'Start Date', 'Amount']">
                    <template slot-scope="{ item }">
                        <tr :key="item.id" class="cursor-pointer hover:bg-grey-lighter" @click="showSip(item)">
                            <td v-text="item.id"></td>
                            <td v-text="item.folio_no"></td>
                            <td v-text="item.client.name"></td>
                            <td class="max-w-64 truncate" v-text="item.scheme.scheme_name"></td>
                            <td class="capitalize" v-text="item.interval"></td>
                            <td v-text="item.installments"></td>
                            <td v-text="item.date"></td>
                            <td>@{{item.amount | currency}} &#x20B9;</td>
                        </tr>
                    </template>
                </v-data-table>
                <modal name="create-sip-form" height="auto" :scrollable="true">
                    <v-ajax-form action="{{ route('admin.sip.store') }}" method="POST" @success="onSuccess" @failure="onFailure">
                        <form class="p-6" slot-scope="{ form, submit, submitting }" @submit.prevent="submit">
                            <h3 class="text-black mb-2">Create SIP</h3>
                            <div class="flex flex-wrap -mx-2 mb-2">
                                <div class="w-1/2 px-2 mb-2">
                                    <v-typeahead src="{{ route('clients.index') }}" 
                                    placeholder="Choose a Client"
                                    v-model="form.client_id"
                                    input-key="id" />
                                        <template slot="selectedItem" slot-scope="{ selected }">
                                            <span v-text="selected.name"></span>
                                        </template>
                                        <template slot-scope="{ item }">
                                            <div class="text-sm truncate" v-text="item.name"></div>
                                            <div class="flex justify-end text-xs">
                                                <span v-text="`PAN: ${item.pan}`"></span>
                                            </div>
                                        </template>
                                    </v-typeahead>
                                </div>
                                <div class="w-1/2 px-2 mb-2">
                                    <v-typeahead src="{{ route('schemes.index') }}" 
                                    v-model="form.scheme_code"
                                    placeholder="Select a Scheme"
                                    input-key="scheme_code" />
                                        <template slot="selectedItem" slot-scope="{ selected }">
                                            <span v-text="selected.scheme_name"></span>
                                        </template>
                                        <template slot-scope="{ item }">
                                            <div class="text-sm truncate" v-text="item.scheme_name"></div>
                                            <div class="flex justify-end text-xs">
                                                <span v-text="item.scheme_plan"></span>
                                                <span class="mx-1">|</span>
                                                <span v-text="item.scheme_type"></span>
                                                <span class="mx-1">|</span>
                                                <span v-text="item.scheme_code"></span>
                                            </div>
                                        </template>
                                    </v-typeahead>
                                </div>
                                <div class="w-1/6 px-2">
                                    <input type="text" v-model="form.folio_no" placeholder="Folio No" class="control">
                                </div>
                                <div class="w-1/3 px-2">
                                    <input type="date" v-model="form.date" placeholder="Sip Date" class="control">
                                </div>
                                <div class="w-1/6 px-2">
                                    <div class="select-wrapper">
                                        <select type="interval" v-model="form.interval" class="control">
                                                <option value="undefined" selected disabled> Select an interval </option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="w-1/6 px-2">
                                    <input type="number" step=".01" v-model="form.amount" placeholder="Amount" class="control">
                                </div>
                                <div class="w-1/6 px-2">
                                    <input type="number" v-model="form.installments" placeholder="Installments" class="control">
                                </div>
                            </div>
                            <div class="flex mt-2 justify-center">
                                <button type="reset" class="btn mr-2" @click="$modal.hide('create-sip-form')"> Cancel </button>
                                <button :disabled="submitting" type="submit" class="btn is-blue" v-text="submitting ? 'Please Wait...' : 'Create SIP' "> </button>
                            </div>
                        </form>
                    </v-ajax-form>
                </modal>
                <modal name="view-sip" :scrollable="true" height="auto">
                    <div v-if="selectedSip" class="p-6">
                        <h3 class="mb-4 capitalize"> SIP Plan (@{{ selectedSip.interval }}) </h3>
                        <div class="flex justify-between mb-2">
                            <div> <span class="font-semibold">Folio:</span> <span v-text="selectedSip.folio_no"></span></div>
                            <div class="text-lg font-bold"><i class="fa fa-user mr-1"></i><span v-text="selectedSip.client.name"></span></div>
                        </div>
                        <div class="flex flex-col justify-between font-semibold mb-2">
                            <div class="flex mb-2">
                                <span class="bg-grey-light text-black border  rounded-l px-2 py-1" v-text="selectedSip.scheme.scheme_code"></span>
                                <div class="flex-1 border rounded-r truncate px-2 py-1" :title="selectedSip.scheme.scheme_name" v-text="selectedSip.scheme.scheme_name"></div>
                            </div>
                            <div class="flex justify-between">
                                <div class="flex items-center -mx-1">
                                    <span class="bg-blue-darker text-white rounded px-2 py-1 mx-1" v-text="selectedSip.scheme.scheme_type"></span>
                                    <span class="bg-grey-darker text-white rounded px-2 py-1 mx-1" v-text="selectedSip.scheme.scheme_plan"></span>
                                </div>
                                <div class="text-2xl"> @{{ selectedSip.amount | currency }}<sup>&#x20B9;</sup> </div>
                            </div>
                        </div>
                        <v-data-table
                            class="flex justify-center mb-2"
                            :data="selectedSip.schedules" 
                            :labels="['Due Date', 'Price', 'Performed?', '']" 
                            :names="['due_date', 'rate', 'executed', '']">
                            <template slot-scope="{ item }">
                                <tr :key="item.id">
                                    <td v-text="item.due_date"></td>
                                    <td v-if="item.rate" v-text="item.rate"></td>
                                    <td v-else> Price will be based on the closing NAV on the due date </td>
                                    <td class="text-center" v-if="item.executed"> <i class="fa fa-check text-green mr-2"></i> Yes </td>
                                    <td class="text-center" v-else> <i class="fa fa-times text-red mr-2"></i> No </td>
                                    <td></td>
                                </tr>
                            </template>
                        </v-data-table>
                        <div class="flex justify-end">
                            <a role="button" class="btn" @click="$modal.hide('view-sip')">Close</a>
                        </div>
                    </div>
                </modal>
            </section>
        </manage-sip-page>
    </main>
</section>
@endsection