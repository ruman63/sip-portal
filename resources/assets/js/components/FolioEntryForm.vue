<script>
export default {
    props: ['action'],
    data() {
        return {
            folios: [],
            form:{
                type: 'fresh',
                folio: '',
                scheme: '',
                transactionId: '',
                date: '',
                purcahse: '',
                amount: '', 
            }
        }
    },
    computed: {
        isFresh() {
            return this.form.type === 'fresh';
        }
    },
    watch: {
        'form.type'() {
            this.changeType();
        }
    },
    methods: {
        changeType() {
            if(!this.isFresh) {
                axios.get('/folios').then(({data}) => {
                    this.folios = data;
                    this.form.folio = this.folios[0].folio_no;
                    this.form.scheme = this.folios[0].scheme;
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
        }
    }
}
</script>
