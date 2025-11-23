new Vue({
  el: '#self_introduction_app',
  data() {
    return {
      text: window.PROFILE_TEXT || "",
      expanded: false,
    }
  },
  computed: {
    lines() {
      return this.text.split(/\r?\n/);
    },
    visibleLines() {
      return this.expanded ? this.lines : this.lines.slice(0, 4);
    }
  }
});
