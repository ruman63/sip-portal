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
            this.transactions.push(transaction);
        },
        updated(transaction) {
            let index = this.transactions.indexOf(
                this.transactions.find(item => item.id==transaction.id)
            );
            if(index >= 0) {
                this.transactions.splice(index, 1, transaction);
            }
        }
    },
    mounted() {
        axios.get('/transactions')
            .then(({data}) => this.transactions = data);
    }
}
</script>
