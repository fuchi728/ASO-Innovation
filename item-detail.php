<?php session_start(); ?>
<?php
$css_files = ['main-style.css', 'title.css', 'item-detail.css'];
require 'header.php';
?>
<?php require 'db-connect.php'; ?>

<!--ページタイトル-->
<?php
// 遷移元取得
$from = $_GET['from'] ?? null;
$item_id = $_GET['item_id'] ?? null;
$other_user_id = $_GET['user'] ?? null;


if ($_SESSION['user']['role'] == 1) {
    $back_link = 'admin-home.php';
} else if ($from === 'mypage') {
    $back_link = 'mypage.php';
} else if ($from === 'other-user') {
    $back_link = 'other-user.php?user=' . urlencode($other_user_id)
        . '&item_id=' . urlencode($item_id)
        . '&from=' . urlencode($from);
} else {
    $back_link = 'item-list.php';
}
?>

<nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center" role="navigation" aria-label="main navigation">
    <a href="<?= $back_link ?>" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small">
            <i class="fas fa-angle-left"></i>
        </span>
    </a>
    <div class="navbar-center">
        <span class="title is-6">商品詳細</span>
    </div>
</nav>

<!--商品詳細-->
<?php
// 商品情報取得
$pdo = new PDO($connect, USER, PASS);
$sql1 = $pdo->prepare('
    select i.*, s.user_id as other_user, u.nickname as seller_nickname,u.profile_image AS seller_profile
    from item i
    left join sell s on i.item_id = s.item_id and s.is_delete = 0
    left join user_info u on s.user_id = u.user_id
    where i.item_id = ?
');
$sql1->execute([$item_id]);
$item = $sql1->fetch(PDO::FETCH_ASSOC);
// 画像情報取得
$sql2 = $pdo->prepare('select image_path from item_image where item_id=?');
$sql2->execute([$item_id]);
$images = $sql2->fetchAll(PDO::FETCH_COLUMN);
// いいね済みチェック
$isLiked = false;
$user_id = $_SESSION['user']['user_id'];
if ($user_id) {
    $sql = $pdo->prepare("select 1 from good where user_id=? and item_id=? and is_delete=0");
    $sql->execute([$user_id, $item_id]);
    $isLiked = $sql->fetch() ? true : false;
}
?>

<!--商品画像表示-->

<div class="content">
    <div class="columns">
        <div class="column">
            <div class="is-flex is-justify-content-center block">
                <!-- 画像スライダー -->
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ($images as $img) {
                            echo '<div class="swiper-slide">';
                            echo '<img class="image_size " src="item-image/', htmlspecialchars($img), '" alt="商品画像">';
                            echo '</div>';
                        };
                        ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <!--商品情報-->
        <div class="column mb-3">
            <div class="block">
                <div class="mb-3 is-flex">
                    <span class="title"><?= htmlspecialchars($item['item_name']) ?></span>
                    <!-- いいね -->
                    <div id="app" class="ml-auto">
                        <i class="fa-heart is-size-2 has-text-danger"
                            :class="liked ? 'fas' : 'far'"
                            @click="toggleLike"></i>
                    </div>
                </div>
                <span class="subtitle is-4">¥<?= number_format($item['price']) ?></span>
            </div>

            <div class="block">
                <div class="mb-3">
                    <span class="title is-6">商品概要</span>
                </div>
                <span><?= htmlspecialchars($item['detail']) ?></span>
            </div>
            <!-- 出品者 -->
            <div class="block">
                <span class="title is-6">出品者</span>
                <div class="is-flex is-align-items-center">
                    <?php
                    if ($user_id != $item['other_user']) {
                        echo '<a href="other-user.php?user=' . urlencode($item['other_user']) . '&from=item-detail&item_id=' . urlencode($item_id) . '" class="is-flex is-align-items-center">';
                    }
                    ?>
                    <figure class="user_icon image is-32x32 m-2">
                        <?php
                        $icon = $item['seller_profile'] ?: 'default.png';
                        ?>
                        <img class="is-rounded" src="user-icon/<?= htmlspecialchars($icon) ?>" alt="ユーザー画像">
                    </figure>
                    <?= htmlspecialchars($item['seller_nickname']) ?>
                    <?php
                    if ($user_id != $item['other_user']) {
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>
        </div>


    </div>
    <!-- コメント -->
    <div class="comment block">
        <div class="mb-3">
            <span class="title is-6">コメント</span>
        </div>
        <div class="comment-scroll-area">
            <?php
            $sql3 = $pdo->prepare('
                        select c.*, u.nickname, u.profile_image
                        from comment c
                        left join user_info u on c.user_id = u.user_id
                        where c.item_id = ? and c.is_delete = 0
                        order by c.comment_time desc
                    ');
            $sql3->execute([$_GET['item_id']]);
            $comments = $sql3->fetchAll(PDO::FETCH_ASSOC);
            foreach ($comments as $comment):
                $icon = $comment['profile_image'] ?: 'default.png';
            ?>
                <div class="is-flex is-align-items-center">
                    <?php
                    if ($user_id != $comment['user_id']) {
                        echo '<a href="other-user.php?user=' . urlencode($comment['user_id']) . '&from=item-detail' . '&item_id=' . urlencode($item_id) . '" class="is-flex is-align-items-center">';
                    }
                    ?>
                    <figure class="user_icon image is-24x24 m-2">
                        <img class="is-rounded" src="user-icon/<?= htmlspecialchars($icon) ?>" alt="ユーザー画像">
                    </figure>
                    <span class="is-size-6"><?= htmlspecialchars($comment['nickname']) ?></span>
                    <?php
                    if ($user_id != $comment['user_id']) {
                        echo '</a>';
                    }
                    ?>
                    <span class="is-size-7 has-text-grey m-2"><?= $comment['comment_time'] ?></span>
                </div>

                <div class="comment_text mb-3">
                    <p><?= nl2br(htmlspecialchars($comment['main_text'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- コメント入力 -->
        <form action="comment-insert.php" method="post">
            <div class="field has-addons pt-3 pr-6 mr-6">
                <div class="control is-expanded">
                    <input class="input" type="text" placeholder="メッセージを入力" name="main_text">
                    <input type="hidden" name="item_id" value="<?= $item_id ?>">
                    <input type="hidden" name="from" value="<?= $from ?>">
                </div>
                <!-- 送信アイコン -->
                <div class="control">
                    <button class="button">
                        <i class="fas fa-pen-square is-size-4"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- 購入ボタン -->
    <?php
    if ($_SESSION['user']['role'] == 1) {
        $action = "item-delete.php";
        $isAdmin = true;
    } else {
        $action = "purchase.php";
        $isAdmin = false;
    }
    ?>
    <form action="<?= $action ?>" method="post">
        <input type="hidden" name="item_id" value="<?= $item_id ?>">
        <input type="hidden" name="from" value=" <?= $from ?>">
        <?php
        if ($item['is_sold'] == 1 && !$isAdmin) {
            // 一般ユーザーで売り切れの場合
            echo '<button disabled id="button" type="submit" class="purchase button is-medium">SOLD OUT</button>';
        } else {
            // ボタンの表示を切り替え
            if ($isAdmin) {
                echo '<button type="submit" class="item_delete button is-medium">商品削除</button>';
            } else {
                echo '<button id="button" type="submit" class="purchase button is-medium">購入</button>';
            }
        }
        ?>
    </form>
</div>

<!-- Swiper読み込み -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper(".mySwiper", {
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>
<!-- いいね -->
<script>
    window.INIT_LIKED = <?= json_encode($isLiked) ?>;
    window.ITEM_ID = <?= json_encode($_GET['item_id']) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
<script src="./script/good.js"></script>

<?php
if ($_SESSION['user']['role'] == 0) {
  require 'footer-menu.php';
}
?>
<?php require 'footer.php'; ?>