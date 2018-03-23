<template>
    <div class="p-3 w-full overflow-x-scroll">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="cursor-pointer" v-for="(column,index) in columns" :key="index+1" @click="sort(column)">
                        <div class="flex">
                            <span class="flex-1" v-text="column === '__actions' ? 'Actions' : column"></span>
                            <i v-if="sortColumn === column" 
                                class="ml-4 fa" 
                                :class="ascending ? 'fa-caret-down' : 'fa-caret-up'">
                            </i>
                        </div>
                    </th> 
                </tr>
            </thead>
            <tbody v-if="!empty">
                <tr  v-for="item in rows" :key="item.id">
                    <td v-for="(column,index) in columns" :key="index+1">
                        <span v-if="column === '__actions'">
                            <slot name="actions"></slot>
                        </span>
                        <span v-else>{{ item[column] }}</span>
                    </td>
                </tr>           
            </tbody>
            <tbody v-else>
                <td :colspan="names.length" class="text-center" v-text="emptyMessage ? emptyMessage : 'No Data!'"></td>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: {
        url: { default: null },
        names: { required: true },
        data: { default: null }
    },
    data () {
        return {
            columns: [],
            rows: [],
            ascending: false,
            sortColumn: '',
            requesting: true
        }
    },
    computed: {
        empty() {
            if(this.url != null && this.requesting) {
                return false;
            }
            return !this.rows.length;
        }
    },
    methods: {
        sort(column) {
            if ( this.sortColumn === column ) {
                this.ascending = !this.ascending
            } else {
                this.sortColumn = column;
                this.ascending = true;
            }
            this.rows.sort((a,b) => {
                if(a[column] < b[column]) {
                    return this.ascending ? -1 : 1 ;
                } else if (a[column]>b[column]){
                    return this.ascending ? 1 : -1;
                } else {
                    return 0;
                }
            });
        }
    },
    mounted() {
        if(this.data == null && this.url != null) {
            axios.get(this.url).then( ({data}) => this.rows = data, this.requesting = false);
        } else {
            this.rows = this.data;
            this.requesting = false;
        }
        

        this.columns = this.names;
    }
}
</script>
