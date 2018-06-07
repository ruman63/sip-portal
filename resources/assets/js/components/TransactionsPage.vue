<template>
    <div>
        <section class="py-4">
            <header class="mb-4 pb-1 border-b-2">
                <h1 v-text="pageTitle">Transactions</h1>
            </header>
            <div class="flex justify-between items-baseline mb-6">
                <div class="select-wrapper w-1/2">
                    <select ref="clientMenu" v-model="clientId" class="control">
                        <option value="">All Clients</option>
                        <option v-for="client in clients" :key="client.id" :value="client.id" v-text="`${client.name} (${client.pan})`"></option>
                    </select>
                </div>
                <button @click.prevent="showCreateForm" class="text-sm btn is-blue">
                    <i class="fa fa-plus mr-1"></i> New Transaction 
                </button>
            </div>
            <transactions-form
                @created="created" 
                @updated="updated" 
                url="/admin/transactions"
                :client-id="clientId"
                inline-template
                >
                <modal name="transaction-form" height="auto" @before-open="beforeOpen">
                    <form @submit.prevent="submit" class="p-8">
                        <h3 class="mb-6" v-text="title"></h3>
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
                                    <option v-for="folio in folios" :key="folio"
                                        :value="folio" 
                                        v-text="folio">
                                    </option>
                                </select>
                                <span v-if="hasErrors('folio_no')" class="text-red text-xs mt-1" v-text="firstError('folio_no')"></span>
                            </div>
                            <div class="field w-3/4 px-1">
                                <label for="scheme_code" class="control">Scheme Code</label>
                                <v-typeahead class="w-full" 
                                    v-model="selectedScheme" 
                                    :url="schemes_url">
                                </v-typeahead>
                                <span v-if="hasErrors('scheme_code')" class="text-red text-xs mt-1" v-text="firstError('scheme_code')"></span>
                            </div>
                            <div class="field w-1/4 px-1">
                                <label for="uid" class="control">Transaction ID</label>
                                <input type="text" v-model="form.uid" id="uid" class="control" required>
                                <span v-if="hasErrors('uid')" class="text-red text-xs mt-1" v-text="firstError('uid')"></span>
                            </div>
                            <div class="field w-1/4 px-1">
                                <label for="date" class="control">Date of Trade</label>
                                <input type="date" v-model="form.date" id="date" class="control" required>
                                <span v-if="hasErrors('date')" class="text-red text-xs mt-1" v-text="firstError('date')"></span>
                            </div>
                            <div class="field w-1/4 px-1">
                                <label for="rate" class="control">Purchase Price</label>
                                <input type="number" step="0.0001" placeholder="0.00" id="rate" class="control" v-model="form.rate" required>
                                <span v-if="hasErrors('rate')" class="text-red text-xs mt-1" v-text="firstError('rate')"></span>
                            </div>
                            <div class="field w-1/4 px-1">
                                <label for="amount" class="control">Amount</label>
                                <input type="number" step="0.0001" placeholder="0.00" id="amount" class="control" v-model="form.amount" required>
                                <span v-if="hasErrors('amount')" class="text-red text-xs mt-1" v-text="firstError('amount')"></span>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn is-blue uppercase text-sm mr-2" v-text="updating ? 'Update':'Create'"></button>
                            <button type="reset" class="btn uppercase text-sm" @click="close">Cancel</button>
                        </div>
                    </form>
                </modal>
            </transactions-form>
            <v-data-table
                class="mb-4"
                :data="transactions"
                :labels="table.header"
                :names="table.row"
                >
                <template slot="header" slot-scope="{ sortColumn, columns, ascending, sort, label }">
                    <tr>
                        <th v-for="(column,i) in columns" :key="i" class="cursor-pointer text-xs" @click="sort(column)">
                            <div class="flex">
                                <span class="flex-1" v-text="label(i)"></span>
                                <i v-if="sortColumn === column" class="ml-2 fa fa-caret-down" 
                                    :class="{ascending:'fa-caret-down'}"></i>
                            </div>
                        </th>
                        <th></th>
                    </tr>
                </template>
                <template slot-scope="{ item }">
                    <tr :key="key(item)"
                        class="text-xs">
                        <td>{{ item.date }}</td>
                        <td v-if="!clientId">{{ client(item.client_id).name }}</td>
                        <td>{{ item.uid }}</td>
                        <td>{{ item.folio_no }}</td>
                        <td>{{ item.scheme.scheme_name }}</td>
                        <td>{{ item.scheme.scheme_type }}</td>
                        <td class="text-right">{{ item.units | fixed }}</td>
                        <td class="text-right">{{ item.rate | currency }} &#x20B9;</td>
                        <td class="text-right">{{ item.amount | currency }} &#x20B9;</td>
                        <td class="w-32 text-right">
                            <button class="btn rounded-full w-8 h-8 p-1 is-blue" @click="edit(item)"><i class="fa fa-edit text-xs"></i></button>
                            <button class="btn rounded-full w-8 h-8 p-1 bg-red hover:bg-red-dark text-white" @click="remove(item)"><i class="fa fa-trash text-xs"></i></button>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </section>
    </div>
</template>

<script>
import TransactionsForm from './TransactionsForm.vue';
export default {
    components: {TransactionsForm},
    data() {
        return {
            transactions: [],
            clients: [],
            clientId: '',
            formUrl: '/admin/transactions',
            table: {
                header: ['Date', 'Client', 'Txn Id', 'Folio', 'Scheme', 'Scheme Type', 'Units', 'Rate', 'Amount'],
                row: ['date', 'client.name', 'uid', 'folio_no', 'scheme.scheme_name', 'scheme.scheme_type', 'units', 'rate', 'amount']
            }
        }
    },
    computed: {
        transactionsUrl() {
            if(this.clientId) {
                return `/admin/clients/${this.clientId}/transactions`;
            }
            return '/admin/transactions';
        },
        selectedClient() {
            return this.client(this.clientId);
        },
        pageTitle() {
            if(this.clientId) {
                return `${this.selectedClient.name}'s Transactions`
            }
            return 'All Transactions';            
        },
        
    },
    watch: {
        clientId(modified, old) {
            if(modified != old) {
                this.fetch();
                if(this.clientId && old == '') {
                    this.table.header.splice(1,1);
                    this.table.row.splice(1,1);
                } else if(!this.clientId) {
                    this.table.header.splice(1,0,'Client');
                    this.table.row.splice(1,0,'client.name');
                }
            }
        }
    },
    methods: {
        client(id){
            return this.clients.find(item => item.id == id);
        },
        fetch(){
            axios.get(this.transactionsUrl).then(({data}) => this.transactions = data);
        },
        edit(transaction) {
            this.$modal.show('transaction-form', {
                transaction, 
            })
        },
        remove(transaction) {
            axios.delete(`/admin/transactions/${transaction.id}`)
                .then(() => {
                    flash('Transactions deleted sucessfully!');
                    let index = this.transactions.indexOf(transaction);
                    if(index>=0) {
                        this.transactions.splice(index,1);
                    }
                }).catch(({response}) => {
                    flash(response.statusText, 'danger');
                });
        },
        key(t) {
            return [t.id, t.uid, t.scheme_code, t.folio_no, t.client_id, t.amount, t.rate].join('|');
        },
        created(transaction) {
            this.transactions.unshift(transaction);
        },
        updated(transaction) {
            let index = this.transactions.indexOf(
                this.transactions.find(item => item.id==transaction.id)
            );
            if(index >= 0) {
                this.transactions.splice(index, 1, transaction);
            }
        },
        showCreateForm() {
            if(this.clientId) {
                this.$modal.show('transaction-form');
            }
            else {
                alert('Please select one client from the dropdown!');
                this.$refs.clientMenu.focus();
            }
        }
    },
    mounted() {
        axios.get('/admin/clients').then(({data}) => this.clients = data);
        this.fetch();
    }
}
</script>
