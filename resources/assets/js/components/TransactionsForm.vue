<script>
export default {
    props: ['url', 'clientId'],
    data() {
        return {
            updating: false,
            schemes_url: '/schemes',
            folios: [],
            selectedScheme: null,
            type: 'fresh',
            errors: {},
            form:{
                id: null,
                uid: '',
                type: 'ADD',
                folio_no: '',
                scheme_code: '',
                client_id: this.clientId,
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
        clientId() {
            this.form.client_id = this.clientId;
        },
        selectedScheme() {
            if(this.selectedScheme) {
                this.form.scheme_code = this.selectedScheme.scheme_code
            }
        }
    },
    methods: {
        hasErrors(property) {
            return this.errors.hasOwnProperty(property) && (this.errors[property].length > 0);
        },
        firstError(property) {
            return this.hasErrors(property) ? this.errors[property][0] : null;  
        },
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
                axios.get('/admin/folios', {
                    params: { client_id: this.clientId }
                }).then(({data}) => {
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
                .then(({data}) => {
                    flash(this.flashMessage);
                    this.$emit((this.updating ? 'updated' : 'created'), data);
                    this.resetAndClose();
                }).catch(({response}) => {
                    if(response.status == 422) {
                        flash(response.data.message, 'danger');
                        this.errors = Object.assign({}, response.data.errors);
                    } else {
                        flash(response.statusText, 'danger');
                    }
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
                client_id: this.clientId,
                date: '',
                rate: '',
                amount: '', 
            };
            this.errors = {};
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
