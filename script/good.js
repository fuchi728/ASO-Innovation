new Vue({
    el: "#app",
    data: {
        liked: window.INIT_LIKED,
        itemId: window.ITEM_ID,
    },
     mounted() {
    console.log("初期liked:", this.liked); // デバッグ用
  },
    methods: {
        toggleLike: function () {
            fetch("../ASO-Innovation/like-insert.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    item_id: this.itemId,
                    liked: this.liked
                }),
                credentials: "same-origin" // セッションを送る
            })
            .then(res => res.json())
            .then(json => {
                if (json.success) {
                    this.liked = json.liked;
                } else {
                    alert(json.error || "通信に失敗しました");
                }
            })
            .catch(() => {
                alert("通信エラーが発生しました");
            });
        }
    }
});
