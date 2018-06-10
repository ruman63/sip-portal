<template>
    <div>
        <table>
            <thead>
                <slot name="header" 
                :sortColumn="sortColumn" 
                :columns="columns"
                :ascending="ascending" 
                :sort="sort" 
                :label="label">
                    <tr>
                        <th class="cursor-pointer" v-for="(column,index) in columns" :key="index+1" @click="sort(column)">
                            <div class="flex">
                                <span class="flex-1" 
                                    v-text="label(index)">
                                </span>
                                <i v-if="sortColumn === column" 
                                    class="ml-4 fa" 
                                    :class="ascending ? 'fa-caret-down' : 'fa-caret-up'">
                                </i>
                            </div>
                        </th> 
                    </tr>
                </slot>
            </thead>
            <tbody v-if="!empty" is="transition-group" name="highlight">
                <template v-for="item in rows">
                    <slot :item="item" :select="select" :getKey="key" :isSelected="isSelected">
                        <tr :key="key(item)">
                            <td v-for="(column,index) in columns" :key="index+1">
                                <span v-if="column === '__actions'">
                                    Actions
                                </span>
                                <span v-else>{{ value(item,column) }}</span>
                            </td>
                        </tr>
                    </slot>           
                </template>
            </tbody>
            <tbody v-else>
                <td :colspan="names.length" class="text-center" v-text="emptyMessage"></td>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: {
        url: { default: null },
        names: { required: true },
        data: { default: null },
        labels: { default: null },
        emptyMessage: {default: 'No Data!'}
    },
    data () {
        return {
            selectedItem: null,
            columns: [],
            rows: [],
            ascending: false,
            sortColumn: '',
            requesting: true
        }
    },
    watch: {
        data() {
            if(this.data) {
                this.rows = this.data;
            }
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
        key(item) {
            let key='';
            this.names.forEach(name => key += this.value(item, name));
            return key;
        },
        select(item) {
            if(this.isSelected(item)) {
                return this.selectedItem = null;
            }
            this.selectedItem = item;
        },
        isSelected(item) {
            if(item == null) {
                return this.selectedItem != null; 
            }
            return this.selectedItem && (this.selectedItem.id == item.id);
        },
        sort(column) {
            if ( this.sortColumn === column ) {
                this.ascending = !this.ascending
            } else {
                this.sortColumn = column;
                this.ascending = true;
            }
            this.rows.sort((a,b) => {
                if( this.value(a, column) < this.value(b, column) ) {
                    return this.ascending ? -1 : 1 ;
                } else if ( this.value(a,column) > this.value(b, column) ){
                    return this.ascending ? 1 : -1;
                } else {
                    return 0;
                }
            });
        },
        label(index) {
            if (this.columns[index] === '__actions') {
                return 'Actions';
            } else if (this.labels && this.labels.length == this.columns.length) {
                return this.labels[index];
            } 
            return this.columns[index];
        },
        value(obj, name) {
            let nest = name.split('.');
            var value = obj;
            for(let i=0; i<nest.length; i++) {
                if(!value) {
                    return null;
                } 
                value = value[nest[i]];
            }
            return value;
        },
        fetchData() {
            axios.get(this.url).then( ({data}) => this.rows = data, this.requesting = false);
        }
    },
    mounted() {
        if (this.data == null && this.url != null) {
            this.fetchData();
        } else {
            this.rows = this.data;
            this.requesting = false;
        }

        this.columns = this.names;
    }
}
</script>
<style>
    .highlight-enter-active{
        transition: background-color 6s ease-in 0.5s, opacity .7s ease-out;
    }
    .highlight-leave-active {
        transition: background-color 1s ease-out, transform 0.1s ease-in 0.8s, opacity 0.1s ease-in 0.8s;        
    }

    .highlight-enter {
        opacity: 0;
        background-color: #FFFF99EE;
    }
    .highlight-leave-to {
        opacity: 0;
        transform: translateX(150%);
        background-color: #f77;
    }
</style>
