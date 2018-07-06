<template>
    <div class="border rounded shadow mb-2 relative p-4">
        <div class="flex items-center justify-end">
            <div v-if="isEditing">
                <button class="btn text-xs p-1 rounded" @click="update">
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn text-xs p-1 rounded" @click="cancel">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div v-else>
                <button class="btn text-xs p-1 rounded"
                    title="Edit"
                    @click="edit">
                    <i class="fa fa-pencil"></i>
                </button>
                <button v-if="account.id != client.default_bank_id" 
                    title="Delete"
                    class="btn is-red text-xs p-1 rounded" 
                    @click="remove">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="md:flex md:items-center mb-2">
            <strong class="w-1/6 mr-3">Account Number</strong> 
            <input v-if="isEditing" type="text" v-model="form.account_number" class="border p-1 rounded">
            <span v-else>{{ account.account_number }}</span>
        </div>
        <div class="md:flex md:items-center mb-2">
            <strong class="w-1/6 mr-3">Account Type</strong>
            <div v-if="isEditing"  class="select-wrapper">
                <select class="border p-1 rounded" v-model="form.account_type_code">
                    <option value="SB">Savings Bank</option>
                    <option value="CB">Current Bank</option>
                    <option value="NE">NRE Account</option>
                    <option value="NO">NRO Account</option>
                </select>
            </div>
            <span v-else >{{ account.account_type_code }}</span>
        </div>
        <div class="md:flex md:items-center mb-2">
            <strong class="w-1/6 mr-3">IFSC Code</strong>
            <input v-if="isEditing" type="text" v-model="form.ifsc_code" class="border p-1 rounded">
            <span v-else>{{ account.ifsc_code }}</span>
        </div>
        <div class="md:flex md:items-center mb-2">
            <strong class="w-1/6 mr-3">Micr No</strong>
            <input v-if="isEditing" type="text" v-model="form.micr" class="border p-1 rounded">
            <span v-else>{{ account.micr }}</span>
        </div>
        <div class="text-right">
            <span v-if="account.id == client.default_bank_id" class="bg-green-dark text-white uppercase tracking-wide font-semibold text-xs p-1 rounded">Default</span>
            <button v-else 
                title="Set as Default"
                class="btn text-sm px-2 py-1 rounded" 
                @click="setAsDefault">
                Mark as Default
            </button>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapState } from "vuex";
export default {
  props: ["data-account-id"],
  data() {
    return {
      form: {},
      isEditing: false
    };
  },
  methods: {
    edit() {
      this.isEditing = true;
    },
    cancel() {
      this.isEditing = false;
    },
    setAsDefault() {
        axios.post('/bank-account/' + this.account.id + '/default')
        .then(({data}) => {
            flash('Marked as your default Bank Account.');
            this.client.default_bank_id = this.account.id;
        });
    },
    update() {
        axios.patch('/bank-account/' + this.account.id, this.form)
        .then(({data}) => {
            this.updateAccount(data.data);
            flash('Successfully Updated!');
            this.isEditing = false;
        });
    },
    remove() {
        axios.delete('/bank-account/' + this.account.id)
        .then(({data}) => {
            this.deleteAccount(this.account);
            flash('Bank account deleted!');
        });
    },
    deleteAccount({id}) {
        let index = this.client.bank_accounts.findIndex(account => account.id === id);
        this.client.bank_accounts.splice(index, 1);
    },
    updateAccount(new_account) {
        let index = this.$store.state.client.bank_accounts.findIndex(account => account.id === new_account.id);
        this.$store.state.client.bank_accounts.splice(index, 1, new_account);
    }
  },
  computed: {
    ...mapState(["client"]),
    ...mapGetters(["accountById"]),
    account() {
        return this.accountById(this.dataAccountId);
    }
  },
  created() {
    this.form = this.account;
  }
};
</script>