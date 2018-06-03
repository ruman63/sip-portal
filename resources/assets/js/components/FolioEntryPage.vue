<script>
import FolioEntryForm from './FolioEntryForm.vue';
export default {
    components: {FolioEntryForm},
    data() {
        return {
            transactions: [],
            editing: false
        }
    },
    methods: {
        edit(transaction) {
            editing: true;
            this.$modal.show('transaction-form', {
                transaction, 
            })
        },
        created(transaction) {
            window.Events.$emit('v-table:add', transaction);
        },
        updated(transaction) {
            wondow.Events.$emit('v-table:update', transaction);
        }
    },
    mounted() {
        axios.get('/transactions')
            .then(({data}) => this.transactions = data);
    }
}
</script>
