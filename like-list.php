<?php
// ========================================
// いいね一覧画面（item_imageテーブルから取得）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php
$css_files = ['main-style.css','like-list.css'];
require 'header.php';
require 'header-menu.php';
?>
<link rel="stylesheet" href="css/like-list.css">

<section class="section has-background-warning-light">
  <div class="container">
    <h2 class="title is-5 mb-4">いいね一覧</h2>

    <div class="item-grid">
      <?php 
        $pdo = new PDO($connect, USER, PASS);
        $sql = $pdo->query('SELECT * FROM item_image WHERE show_home = 1');
        $items = $sql->fetchAll();

        if (empty($items)): ?>
          <p>現在「いいね」された商品はありません。</p>
      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <div class="item">
            <img src="item-image/<?= htmlspecialchars($item['image_path']) ?>" alt="商品画像">
            <p>商品ID：<?= htmlspecialchars($item['item_id']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
