<script>
export default {
    props: ['action', 'method'],
    data() {
        return {
            submitting: false,
            form: {
                
            },
            errors: {

            }
        }
    },
    methods: {
        submit() {
            this.submitting = true;
            axios[this.method.toLowerCase()](this.action, this.form)
                .then(response => {
                    this.submitting = false;
                    this.$emit('success', response);
                })
                .catch(error => {
                    if(error.response.status == 422) {
                        this.errors = error.response.errors;
                    }
                    this.submitting = false;
                    this.$emit('failure', error);
                });
        },
        reset() {
            this.form = {}
        }
    },
    render() {
        let root = this.$scopedSlots.default({
            form: this.form,
            submit: this.submit,
            submitting: this.submitting,
        });
        return root;
    }
}
</script>
