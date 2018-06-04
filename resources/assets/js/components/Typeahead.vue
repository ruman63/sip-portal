<template>
  <div>
    <!-- optional indicators -->
    <!-- <i class="absolute  fa fa-spinner fa-spin" v-if="loading"></i> -->

    <!-- the input field -->
    <input type="text"
           class="control"
           placeholder="Search Schemes..."
           autocomplete="off"
           v-model="query"
           @keydown.down="down"
           @keydown.up="up"
           @keydown.enter.prevent="hit"
           @keydown.esc="reset"
           @blur="blur"
           @focus="focus"
           @input="update"/>

    <input type="hidden" :name="name" v-model="data.scheme_code">

    <!-- the list -->
    <div v-show="show" class="relative">
        <ul v-if="hasItems" 
        ref="options"
        class="absolute pin-t pin-x list-reset p-1 bg-white border h-64 overflow-y-scroll mt-1 border-solid border-grey">
          <!-- for vue@1.0 use: ($item, item) -->
          <li v-for="(item, $item) in items" 
            :key="item.unique_no"
            :class="activeClass($item)" 
            class="py-1 px-3 rounded"
            @mousedown="hit" 
            @mousemove="setActive($item)">
              <span v-text="`[${item.scheme_code}] ${item.scheme_name}`"></span>
          </li>
        </ul>
        <div v-else 
          v-text="empty"
          class="absolute pin-t pin-x p-1 bg-white border mt-1 border-solid border-grey">
        </div>
    </div>
  </div>
</template>

<script>
import VueTypeahead from '../mixins/Typeahead';
export default {
  extends: VueTypeahead, // vue@1.0.22+
  // mixins: [VueTypeahead], // vue@1.0.21-
  props: {
    'url':{},
    'name':{},
    'value':{}
  },
  data () {
    return {
      // The source url
      // (required)
      src: this.url,

      // The data that would be sent by request
      // (optional)
      data: {},

      // Limit the number of items which is shown at the list
      // (optional)
      // limit: 5,

      // The minimum character length needed before triggering
      // (optional)
      minChars: 3,

      time: 300,

      // Highlight the first item in the list
      // (optional)
      selectFirst: false,

      // Override the default value (`q`) of query parameter name
      // Use a falsy value for RESTful query
      // (optional)
      queryParamName: 's'
    }
  },
  watch: {
    value() {
      if(this.value) {
        this.onHit(this.value);
      }
    }
  },
  methods: {
    // The callback function which is triggered when the user hits on an item
    // (required)
    onHit (item) {
      this.data = item;
      this.query = `[${this.data.scheme_code}] ${this.data.scheme_name}`;
      this.$emit('input', item);
    },

    // The callback function which is triggered when the response data are received
    // (optional)
    prepareResponseData (data) {
      // data = ...
      return data;
    },

    activeClass(index) {
        return index === this.current ? 'bg-blue-darkest text-white' : 'text-black';
    }
  },
  mounted() {
    if(this.value) {
      this.onHit(this.value);
    }
  }
}
</script>

