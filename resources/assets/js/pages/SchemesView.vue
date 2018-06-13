<script>
import debounce from 'lodash.debounce';
export default {
    data() {
        return {
            schemes: [],
            types: [],
            agents: [],
            query: '',
            type: '',
            agent: '',
        };
    },
    methods: {
        search: debounce(function() {
            this.fetchSchemes();
        }, 330),
        clear() {
            this.query = '';
            this.type = '';
            this.agent = '';
            this.fetchSchemes();
        },
        fetchSchemes() {
            return axios.get('/schemes', {
                params: {
                    s: this.query,
                    type: this.type,
                    agent: this.agent,
                }
            }).then(({data}) => this.schemes = data.data);
        },
        onSuccess(response)
        {
            flash('Schemes will be generated soon. <br> File upload successful!', 'success');
        },
        onFailure(response)
        {
            flash(response.statusText, 'danger');
        }
    },
    mounted() {
        this.fetchSchemes()
        axios.get('/schemes/types').then(({data}) => this.types = data );
        axios.get('/schemes/agents').then(({data}) => this.agents = data );
    }
}
</script>