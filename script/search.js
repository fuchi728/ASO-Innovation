new Vue({
  el: '#app',
  data() {
    return {
      showSearch: false,
    };

  },
  methods: {
    toggleSearch() {
      this.showSearch = !this.showSearch;
    }
  }
});
