<?php require 'db-connect.php'; ?>
<?php
$css_files = ['main-style.css', 'admin-header.css', 'admin-news-detail-style.css'];
require 'admin-header.php';
?>

<?php
$id = (int)$_GET['id'];

$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->prepare('select * from news where news_id = ?');
$sql->execute([$id]);
$news = $sql->fetch(PDO::FETCH_ASSOC);

echo '<section class="section">';
echo '<div class="container">';
echo '<h1 class="title">', $news['title'], '</h1>';
echo '<p class="is-size-7 has-text-grey">', $news['send_time'], '</p>';
echo '<hr>';
echo '<div class="content">', nl2br($news['detail']), '</div>';
echo '<a href="admin-news-list.php" class="button is-light mt-4">戻る</a>';
echo '</div>';
echo '</section>';

?>

<?php require 'footer.php'; ?>