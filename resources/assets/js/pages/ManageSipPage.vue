<script>
export default {
    props: ['data'],
    data() {
        return {
            selectedSip: null,
            entries: this.data
        }
    },
    methods: {
        showSip(sip) {
            this.selectedSip = sip;
            this.$modal.show('view-sip');
        },
        onSuccess({data}) {
            this.$modal.hide('create-sip-form');
            this.entries.unshift(data);
            flash('Sip Created Successfully!', 'success');
        },
        onFailure({response}) {
            flash(`${response.status}: ${response.statusText}`, 'danger');
        }
    }
}
</script>
