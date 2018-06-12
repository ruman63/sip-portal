<script>
import debounce from 'lodash.debounce';
export default {
    data() {
        return {
            schemes: [],
            query: '',
            type: '',
            agent: '',
        };
    },
    methods: {
        search: debounce(function() {
            this.fetchSchemes().then(({data}) => this.schemes = data.data);
        }, 330),
        fetchSchemes() {
            return axios.get('/schemes', {
                params: {
                    s: this.query,
                    type: this.type,
                    agent: this.agent,
                }
            });
        }
    },
    mounted() {
        this.fetchSchemes().then(({data}) => this.schemes = data.data)
    }
}
</script>