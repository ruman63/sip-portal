<script>
export default {
    methods: {
        success(response) {
            flash("Import Successful", 'success');
            this.$modal.hide('import-csv-data');
        },
        failure(error) {
            if(error.response.status != 422) {
                flash(error.response.statusText, 'danger');
            }
        }
    }
}
</script>