new Vue({
  el: '#app',
  data() {
    return {
      history: [],
      total: 0,
      limit: 4,
      expanded: false
    };
  },
  methods: {
    loadHistory() {
      fetch(`history-api.php?limit=${this.limit}`)
        .then(res => res.json())
        .then(data => {
          this.total = data.total;
          this.history = data.items;
        });
    },
    showMore() {
      this.limit = 20;
      this.expanded = true;
      this.loadHistory();
    },
    closeMore() {
      this.limit = 4;
      this.expanded = false;
      this.loadHistory();
    }
  },
  mounted() {
    this.loadHistory();
  }
});
