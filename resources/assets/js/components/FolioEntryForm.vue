<script>
export default {
    props: ['url'],
    data() {
        return {
            updating: false,
            folios: [],
            selectedScheme: null,
            type: 'fresh',
            form:{
                id: null,
                uid: '',
                type: 'ADD',
                folio_no: '',
                scheme_code: '',
                date: '',
                rate: '',
                amount: '', 
            }
        }
    },
    computed: {
        isFresh() {
            return this.type === 'fresh';
        },
        requestMethod() {
            return this.updating ? 'patch' : 'post';
        },
        route(){
            return this.updating ? `${this.url}/${this.form.id}` : this.url;
        },
        flashMessage() {
            return `Transaction ${this.updating ? 'updated' : 'created'} Successfully!`
        },
        title(){
            return `${this.updating ? 'Edit' : 'New'} Transaction`;
        }
    },
    watch: {
        type() {
            this.changeType();
        },
        selectedScheme() {
            if(this.selectedScheme) {
                this.form.scheme_code = this.selectedScheme.scheme_code
            }
        }
    },
    methods: {
        beforeOpen(event) {
            if(event.params && event.params.transaction) {
                this.form = Object.assign({}, event.params.transaction) ;
                this.selectedScheme = this.form.scheme;
                this.updating = true;
            } else  if(this.updating) {
                this.reset();
            }
        },
        changeType() {
            if(!this.isFresh) {
                axios.get('/folios').then(({data}) => {
                    this.folios = data;
                    this.form.folio_no = this.folios[0];
                });
            } 
            else {
                this.form.folio_no = '';
            }
        },
        submit() {
            axios[this.requestMethod](this.route, this.form)
                .catch(({response}) => console.log(response.data.errors))
                .then(({data}) => {
                    flash(this.flashMessage);
                    this.$emit((this.updating ? 'updated' : 'created'), data);
                    this.resetAndClose();
                });
        },
        reset() {
            this.updating = false;
            this.selectedScheme = null;
            this.form = {
                type: 'ADD',
                folio_no: '',
                scheme_code: '',
                uid: '',
                date: '',
                rate: '',
                amount: '', 
            };
        },
        close() {
            this.$modal.hide('transaction-form');
        },
        resetAndClose() {
            this.reset();
            this.$modal.hide('transaction-form');
        }
    }
}
</script>
