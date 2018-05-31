<script>
export default {
    props: ['route'],
    data() {
        return {
            folios: [],
            type: 'fresh',
            form:{
                type: 'ADD',
                folio_no: '1234',
                scheme_code: '',
                transaction_uid: 't-123',
                date: '2017-11-12',
                rate: '321',
                amount: '24000', 
            }
        }
    },
    computed: {
        isFresh() {
            return this.type === 'fresh';
        }
    },
    watch: {
        type() {
            this.changeType();
        }
    },
    methods: {
        changeType() {
            if(!this.isFresh) {
                axios.get('/folios').then(({data}) => {
                    this.folios = data;
                    this.form.folio = this.folios[0];
                });
            } 
            else {
                this.form.folio = '';
                this.form.scheme = '';
            }
        },
        changeFolio() {
            let selectedFolio = this.folios.find(item => item.folio_no == this.form.folio);
            this.form.scheme = selectedFolio.scheme;
        },
        submit() {
            axios.post(this.route, this.form)
                .catch(({response}) => console.log(response.data.errors))
                .then(() => flash('Transaction Added Successfully'));
        }
    }
}
</script>
