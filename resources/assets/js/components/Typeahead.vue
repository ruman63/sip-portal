<template>
    <div @blur="close">
        <button class="border px-3 py-2 w-full text-left focus:border-blue-darker" @click="toggle">
            <span v-if="isSelected">
                <slot name="selectedItem" :selected="items[selectedIndex]">{{ items[selectedIndex] }}</slot>
            </span>
            <span v-else class="text-grey-dark" v-text="placeholder"></span>
        </button>
        <div v-show="isOpen" class="relative">
            <div class="absolute border mt-2 pin-t pin-x flex flex-col p-3">
                <input ref="input" 
                  type="text"
                  v-model="query"
                  @input="input"
                  @keydown.down="highlightNext"
                  @keydown.up="highlightPrev"
                  class="control"
                  autofocus/>
                <ul ref="options" v-if="items.length > 0" class="list-reset mt-2 h-64 overflow-x-scroll">
                    <li v-for="(item,i) in items" :key="i"
                        class="py-2 px-3 cursor-pointer rounded" 
                        :class="{'bg-blue-darker text-white':isHighlighted(i)}"
                        @click="select(i)"
                        @mouseover="highlight(i)">
                        <slot :item="item">
                        </slot>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</template>

<script>
import debounce from 'lodash.debounce';
export default {
    props: {
        placeholder: { default: "Select an Item..." },
        src: {required: true }
    },
    data() {
        return {
            query: '',
            isOpen: false,
            items: [],
            selectedIndex: -1,
            highlightedIndex: -1,
        }
    },
    methods:{
        input: debounce(function() {
            this.makeRequest().then(({data}) => this.items = data);
        }, 500),
        makeRequest() {
            return axios.get(this.src, {params:{ s:this.query } });
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
        },
        select(i) {
            if( i >= 0 && i<this.items.length ) {
                this.selectedIndex = i;
                this.close();
            }
        },
        isSelected() {
            return this.selectedIndex >= 0;
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
        }
    },
    mounted() {
        axios.get(this.src).then(({data}) => this.items = data);
    }
};
</script>

