<template>
    <div>
        <button class="relative text-left text-sm bg-white w-full py-2 px-2 border h-10 truncate" @click.prevent="toggle">
            <slot name="selectedItem" v-if="selectedItem" :selected="selectedItem">
                {{ selectedItem }}
            </slot>
            <span class="text-grey-dark" v-text="placeholder" v-else></span>
            <span v-if="loading" class="absolute pin-y pin-r pr-2 flex items-center justify-center">
                <div class="border-4 border-blue-darker w-6 h-6 rounded-full"
                    style="border-top-color: transparent; animation: rotate 0.6s linear infinite;"
                ></div>
            </span>
        </button>
        <div v-show="isOpen" class="relative z-10">
            <div class="absolute border mt-2 pin-t pin-x flex flex-col p-3 bg-white rounded shadow-lg">
                <input ref="input" 
                  type="text"
                  v-model="query"
                  @input="input"
                  @keydown.down="highlightNext"
                  @keydown.up="highlightPrev"
                  @keydown.enter.prevent="select(highlightedIndex)"
                  @keydown.esc.prevent="close"
                  class="control" />
                <ul v-if="!isEmpty" ref="options" class="list-reset mt-2 h-64 overflow-y-scroll">
                    <li v-for="(item,i) in items" :key="item[inputKey]"
                        class="py-2 px-3 cursor-pointer rounded" 
                        :class="{'bg-blue-darker text-white':isHighlighted(i)}"
                        @click="select(i)"
                        @mouseover="highlight(i)">
                        <slot :item="item" :log="log">
                        </slot>
                    </li>
                </ul>
                <slot name="empty" v-if="isEmpty">
                    <div class="py-2 mt-2 text-center text-grey font-semibold"> Sorry, No Results :( </div>
                </slot>
            </div>

        </div>
    </div>
</template>

<script>
import debounce from 'lodash.debounce';
export default {
    props: {
        placeholder: { default: "Select an Item..." },
        src: {required: true },
        paramName: {default: 'q'},
        inputKey: {required: true},
        value: {},
    },
    data() {
        return {
            query: '',
            loading: false,
            isOpen: false,
            items: [],
            selectedIndex: -1,
            highlightedIndex: -1,
        }
    },
    watch: {
        value() {
            if(this.value) {
                this.assignValue(this.value);
            }
        }
    },
    computed: {
        selectedItem() {
            if(this.selectedIndex >= 0 && this.selectedIndex < this.items.length) {
                return this.items[this.selectedIndex];
            }
            return null;
        },
        isEmpty() {
            return !(this.items.length > 0);
        }
    },
    methods:{
        log(ob) {
            console.log(ob);
        },
        input: debounce(function() {
            this.makeRequest().then(({data}) => {
                this.items = data;
                this.loading = false;
            });
        }, 500),
        makeRequest() {
            let params = {};
            params[this.paramName] = this.query;
            this.loading = true;
            return axios.get(this.src, {params});
        },
        toggle() {
            if(this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },
        open() {
            this.isOpen = true;
            this.$nextTick(() => this.$refs.input.focus() );
        },
        close() {
            this.isOpen = false;
            this.query = '';
        },
        select(i) {
            if( i >= 0 && i<this.items.length ) {
                this.selectedIndex = i;
                let selected = this.selectedItem;
                if(this.inputKey) {
                    selected = this.selectedItem[this.inputKey];
                }
                this.$emit('input', selected);
                this.close();
            }
        },
        highlight(i) {
            this.highlightedIndex = i;
            if(this.highlightedIndex >= this.items.length ) {
                this.highlightedIndex = this.items.length-1;
            }
            if(this.highlightedIndex < 0) {
                this.highlightedIndex = 0;
            }
            this.$refs.options.children[this.highlightedIndex].scrollIntoView({ block: 'nearest' });
        },
        highlightNext() {
            this.highlight(this.highlightedIndex + 1);
        },
        highlightPrev() {
            this.highlight(this.highlightedIndex - 1);
        },
        isHighlighted(i) {
            if(i != null) {
                return i==this.highlightedIndex;
            }
            return this.highlightedIndex >= 0;
        },
        assignValue(value) {
            let callback = typeof(value) != 'object' ? 
                (item => item[this.inputKey] == value) : 
                (item => item[this.inputKey] == value[this.inputKey]);
            this.selectedIndex = this.items.findIndex(callback);
        }
    },
    mounted() {
        this.makeRequest().then(({data}) => { 
            this.loading = false;
            this.items = data;
            if(this.value != null && this.value != '') {
                this.assignValue(this.value)
            }
        });
    }
};
</script>

