<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$css_files = ['main-style.css','sell-list.css'];
require 'header.php';
require 'header-menu.php';
?>

<link rel="stylesheet" href="css/sell-list.css">

<section class="section">
  <div class="container">
    <h2 class="title">出品一覧</h2>
    <?php
      $pdo = new PDO($connect, USER, PASS);
      $sql = $pdo->query('SELECT s.sell_id, i.image_path FROM sell s JOIN item_image i ON s.item_id=i.item_id WHERE s.is_delete=0');
      $sells = $sql->fetchAll();
      echo '<p>出品数：'.count($sells).'</p>';
    ?>
    <div class="item-grid">
      <?php if (empty($sells)): ?>
        <p>現在出品されている商品はありません。</p>
      <?php else: foreach ($sells as $sell): ?>
        <div class="item"><img src="item-image/<?= htmlspecialchars($sell['image_path']) ?>" alt="商品画像"></div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
