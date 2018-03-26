import { util } from 'vue'
import debounce from 'lodash/debounce';

export default {
  props: {
    empty: {default: 'Nothing Found!'}
  },
  data () {
    return {
      items: [],
      query: '',
      current: -1,
      show: false,
      loading: false,
      selected: false,
      selectFirst: false,
      queryParamName: 'q'
    }
  },

  computed: {
    hasItems () {
      return this.items.length > 0
    },

    isEmpty () {
      return !this.query
    },

    isDirty () {
      return !!this.query
    }
  },

  methods: {
    update: debounce(function() {
      this.cancel();
      
      if (!this.query) {
        return this.reset();
      }

      if (this.minChars && this.query.length < this.minChars) {
        return;
      }

      this.loading = true;
      this.fetch().then((response) => {
        if (response && this.query) {
          let data = response.data;
          data = this.prepareResponseData ? this.prepareResponseData(data) : data;
          this.items = this.limit ? data.slice(0, this.limit) : data;
          this.current = -1;
          this.loading = false;
          this.show = true;
          this.selected = false;

          if (this.selectFirst) {
            this.down();
          }
        }
      })
    }, this.time ? this.time : 600),

    fetch () {
      
      if (!this.src) {
        return util.warn('You need to set the `src` property', this)
      }
      const src = this.queryParamName
      ? this.src
      : this.src + this.query

      const params = this.queryParamName
        ? Object.assign({ [this.queryParamName]: this.query }, this.data)
        : this.data

      let cancel = new Promise((resolve) => this.cancel = resolve)
      let request = axios.get(src, { params })

      return Promise.race([cancel, request])
    
    },

    cancel () {
      // nothing here.
      // used to 'cancel' previous searches
    },

    blur () {
      if(this.selected) {
        this.show = false;
      } else {
        this.reset();
      }
    },

    focus () {
      this.show = false;
      this.selected = false;
    },

    reset () {
      this.items = [];
      this.show = false;
      this.selected = false;
      this.query = '';
      this.loading = false;
    },

    setActive (index) {
      this.current = index
    },

    activeClass (index) {
      return {
        active: this.current === index
      }
    },

    hit () {
      if (this.current !== -1) {
        this.show = false;
        this.selected = true;
        this.onHit(this.items[this.current])
      }
    },

    up () {
      if (this.current > 0) {
        this.current--
      } else if (this.current === -1) {
        this.current = this.items.length - 1
      } else {
        this.current = -1
      }
    },

    down () {
      if (this.current < this.items.length - 1) {
        this.current++
      } else {
        this.current = -1
      }
    },

    onHit () {
      util.warn('You need to implement the `onHit` method', this)
    }
  }
}
