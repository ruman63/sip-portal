import {Store} from 'vuex';

export default new Store({
    state: {
        client: {}
    },
    getters: {
        name: state => state.client.first_name + ' ' + state.client.last_name,
        gender: state => state.client.gender == 'M' ? 'Male' : 'Female',
        accountById: state => (id) => state.client.bank_accounts.find(account => account.id === id)
    }
});