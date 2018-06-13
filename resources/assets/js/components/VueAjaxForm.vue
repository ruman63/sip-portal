<script>
export default {
    props: {
        'action': {required: true},
        'method': {default: 'get'},
        'useFormData': {default: false, type: Boolean}, 
    },
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
            let formData = this.getData();
            let config = this.makeConfig();
            this.submitting = true;
            axios[this.method.toLowerCase()](this.action, formData, config)
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
        inputFile(event) {
            console.log(event);
            let el = event.target;
            this.form[el.name] = el.files[0];
        },
        makeConfig() {
            if(!this.useFormData) {
                return {}
            }
            return {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        },
        getData() {
            if(!this.useFormData) {
                return this.form;
            }
            return this.makeFormData();
        },
        makeFormData() {
            let formData = new FormData();
            Object.getOwnPropertyNames(this.form).forEach(prop => {
                formData.append(prop, this.form[prop]);
            })
            return formData;
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
            updateFile: this.inputFile,
        });
        return root;
    }
}
</script>
