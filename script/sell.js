new Vue({
  el: '#sell_app',
  data() {
    return {
      onlyAvailable: false,
      selectedCategory: 0, // 0 = 全カテゴリ
      sell_list: window.INIT_SELLS || [],
      categories: window.CATEGORIES || []
    };
  },
  computed: {
    sell() {
      let list = this.sell_list;

      // 販売中のみ
      if (this.onlyAvailable) {
        list = list.filter(item => item.item_is_sold == 0);
      }

      // カテゴリ絞り込み
      if (this.selectedCategory != 0) {
        list = list.filter(item => item.category_id == this.selectedCategory);
      }

      return list;
    }
  }
});
