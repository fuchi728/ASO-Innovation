<?php
session_start();
require_once 'db-connect.php'; ?>
<?php
$css_files = ['main-style.css', 'notice-style.css'];
require 'header.php';
require 'header-menu.php';

// タブのリンク切り替え
$notice_link = isset($_SESSION['user']['user_id'])
    ? "notice-1.php"
    : "login.php";
?>

<div class="tabs is-fullwidth">
    <ul>
        <li><a href="<?= $notice_link ?>">お知らせ</a></li>
        <li class="is-active"><a href="notice-2.php">NEWS</a></li>
    </ul>
</div>

<?php
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->query('select * from news order by send_time desc');

foreach ($sql as $row) {
    echo '<section class="section py-4">';
    echo '<div class="container">';
    echo '<a href="news-detail.php?id=', $row['news_id'], '" class="box notice-box is-flex is-justify-content-space-between is-align-items-center">';
    echo '<div class="is-flex is-flex-direction-column">';
    echo '<p>', $row['title'], '</p>';
    echo '<p class="is-size-7 has-text-grey">', $row['send_time'], '</p>';
    echo '</div>';
    echo '<span class="icon is-medium has-text-grey">';
    echo '<i class="fas fa-angle-right"></i>';
    echo '</span>';
    echo '</a>';
    echo '</div>';
    echo '</section>';
}

?>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>