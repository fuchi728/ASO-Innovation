<?php require 'db-connect.php'; ?>
<?php
$page_title = 'NEWS発信';
$css_files = ['main-style.css', 'admin-header.css', 'admin-news-style.css'];
require 'admin-header.php';
?>

<div class="tabs is-fullwidth">
    <ul>
        <li class="is-active"><a href="notice-2.php">NEWS</a></li>
    </ul>
</div>

<?php
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->query('select * from news order by send_time desc');

foreach ($sql as $row) {
    echo '<section class="section py-4">';
    echo '<div class="container">';
    echo '<a href="admin-news-detail.php?id=', $row['news_id'], '" class="box notice-box is-flex is-justify-content-space-between is-align-items-center">';
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

<!-- ボタン -->
<div class="fixed-buttons">
    <a href="admin-news.php" class="button is-warning">
        投稿
    </a>
</div>


<?php require 'footer.php'; ?>