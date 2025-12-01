<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>

<?php
$css_files = ['main-style.css', 'dm-list-style.css', 'title.css'];
require 'header.php';
?>
<?php require 'header-menu.php'; ?>

<nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center">
    <a href="Listing Status Screen.php" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small"><i class="fas fa-angle-left"></i></span>
    </a>
    <div class="navbar-center">
        <span class="title is-6">取引一覧</span>
    </div>
</nav>

<?php
// ログイン中ユーザーIDを取得
$user_id = $_SESSION['user']['user_id'];

$pdo = new PDO($connect, USER, PASS);

$sql = $pdo->prepare("
(
    SELECT
        s.item_id,
        i.item_name,
        b.user_id AS partner_id,
        u.nickname AS partner_name,
        b.buy_time AS related_time,
        u.profile_image AS partner_icon,
        '購入者' AS role
    FROM sell s
    JOIN buy b       ON b.item_id = s.item_id AND b.is_delete = 0
    JOIN item i      ON i.item_id = s.item_id
    JOIN user_info u ON u.user_id = b.user_id
    WHERE s.user_id = :uid
    AND s.is_delete = 0
)

UNION ALL

(
    SELECT
        b.item_id,
        i.item_name,
        s.user_id AS partner_id,
        u.nickname AS partner_name,
        s.sell_time AS related_time,
        u.profile_image AS partner_icon,
        '出品者' AS role
    FROM buy b
    JOIN sell s      ON s.item_id = b.item_id AND s.is_delete = 0
    JOIN item i      ON i.item_id = b.item_id
    JOIN user_info u ON u.user_id = s.user_id
    WHERE b.user_id = :uid
    AND b.is_delete = 0
)

ORDER BY related_time DESC
");

$sql->execute(['uid' => $user_id]);
$dm_list = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($dm_list as $row) {
    echo '<section class="section py-4">';
    echo '<div class="container">';
    // DMページへ (item_id と 相手ユーザーID を渡す)
    echo '<a href="dm-detail.php?item_id=' . $row['item_id'] . '&partner_id=' . $row['partner_id'] . '" class="box notice-box">';
    // ユーザー名
    echo '<div class="is-flex is-align-items-center mb-1">';
    echo '<strong>' . htmlspecialchars($row['role']) . '：</strong>';
    $icon = $row['partner_icon'] ? htmlspecialchars($row['partner_icon']) : 'default.png';
    echo '<figure class="user_icon image is-32x32 mr-1">';
    echo '<img class="is-rounded" src="user-icon/' . $icon . '">';
    echo '</figure>';
    echo '<p>' . htmlspecialchars($row['partner_name']) . '</p>';
    echo '</div>';
    // 商品名
    echo '<div class="mb-1">';
    echo '<p><strong>商品：</strong>' . htmlspecialchars($row['item_name']) . '</p>';
    echo '</div>';


    // 関係ができた日時（買った or 出品した）
    echo '<p class="is-size-7 has-text-grey">取引開始：' . $row['related_time'] . '</p>';
    echo '<span class="icon is-medium has-text-grey" style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">';
    echo '<i class="fas fa-angle-right"></i></span>';

    echo '</a>';
    echo '</div>';
    echo '</section>';
}
?>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>