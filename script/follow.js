new Vue({
    el: "#follow_app",
    data: {
        following: window.INIT_FOLLOWING,
        followedId: window.FOLLOWED_ID,
    },
    methods: {
        toggleFollow: function () {
            fetch("../ASO-Innovation/follow-insert.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ followed_id: this.followedId }),
                credentials: "same-origin"
            })
            .then(res => res.json())
            .then(json => {
                if (json.success) {
                    this.following = json.following;
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
