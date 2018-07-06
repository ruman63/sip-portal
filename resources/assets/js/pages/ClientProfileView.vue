<template>
    <section class="py-8">
        <header class="flex page-header">
            <name-editor :data-name="{first_name: client.first_name, last_name: client.last_name}">
            </name-editor>
        </header>

        <basic-information></basic-information>
    
        <address-editor></address-editor>
        
        <bank-accounts-list></bank-accounts-list>
    </section>
</template>

<script>
import { mapState, mapGetters } from 'vuex';
import BasicInformation from './client-profile/BasicInformation.vue';
import NameEditor from './client-profile/NameEditor.vue';
import AddressEditor from './client-profile/AddressEditor.vue';
import BankAccountsList from './client-profile/BankAccountsList.vue';
import store from './client-profile/store';
export default {
    props: ['data-client'],
    store, 
    components: {BasicInformation, NameEditor, AddressEditor, BankAccountsList},
    computed: {
        ...mapState(['client']),
        ...mapGetters(['name'])
    },
    created() {
        this.$store.state.client = this.dataClient;
    }
}
</script>