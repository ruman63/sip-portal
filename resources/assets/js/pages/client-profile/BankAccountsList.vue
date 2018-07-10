<template>
    <section class="mb-4 py-8">
        <div class="flex items-center justify-between mt-2 mb-6">
            <h2 class="mr-4">Bank Account Details</h2>
            <div v-if="isCreating">
                <button class="btn is-blue" @click="store">
                    <i class="fa fa-check"></i> Create
                </button>
                <button class="btn" @click="cancel">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
            <button v-else class="btn is-blue" @click="create">
                <i class="fa fa-plus mr-2"></i> Add
            </button>
        </div>
        <transition name="slide-up">
            <div v-if="isCreating" class="md:flex md:items-center -mx-1 mb-8">
                <div class="px-1">
                    <label class="control">Type</label>
                    <div class="select-wrapper">
                        <select class="border p-1 rounded" v-model="newAccount.account_type_code">
                            <option value="undefined">Account Type</option>
                            <option value="SB">Savings Bank</option>
                            <option value="CB">Current Bank</option>
                            <option value="NE">NRE Account</option>
                            <option value="NO">NRO Account</option>
                        </select>
                    </div>
                </div>
                <div class="px-1 flex-1">
                    <label class="control">A/c Number</label>
                    <input type="text" v-model="newAccount.account_number" placeholder="Account Number" class="w-full border p-1 rounded">
                </div>
                <div class="px-1 flex-1">
                    <label class="control">IFSC Code</label>
                    <input type="text" v-model="newAccount.ifsc_code" placeholder="IFSC Code" class="w-full border p-1 rounded">
                </div>
                <div class="px-1 flex-1">
                    <label class="control">MICR No</label>
                    <input type="text" v-model="newAccount.micr" placeholder="MICR" class="w-full border p-1 rounded">
                </div>
            </div>
        </transition>
        <bank-account v-for="account in accounts" :key="account.id" :data-account-id="account.id">

        </bank-account>
    </section>
</template>
<script>
import BankAccount from './BankAccount.vue';
import {mapState} from 'vuex';
export default {
    components: {BankAccount},
    data() {
        return {
            newAccount: {},
            isCreating: false,
        };
    },
    methods: {
        create() {
            this.isCreating = true;
        },
        cancel() {
            this.isCreating = false;
        },
        store() {
            axios.post('/bank-account', this.newAccount)
                .then(({data}) => {
                    this.addBankAccount(data.data);
                    this.newAccount = {}
                    flash('Success! Added new Bank Account');
                    this.isCreating = false;
                });
        },
        addBankAccount(account) {
            this.accounts.unshift(account);
        }
    },
    computed: {
        ...mapState({
            accounts: state => state.client.bank_accounts,
        }),
    }
}
</script>
<style>
    .slide-up-enter-active, .slide-up-leave-active {
        transition: all 0.6s ease-in;
    }
    .slide-up-enter, .slide-up-leave-to {
        transform: translateY(-80%);
        opacity: 0;
    }
</style>
