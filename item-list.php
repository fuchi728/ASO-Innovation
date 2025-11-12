<?php
// ========================================
// 商品一覧画面（ソート付き）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db-connect.php';
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);
$order = $_GET['sort'] ?? 'price_asc';
$sql = match($order) {
  'price_desc' => 'SELECT * FROM item ORDER BY price DESC',
  'id_desc'    => 'SELECT * FROM item ORDER BY item_id DESC',
  default      => 'SELECT * FROM item ORDER BY price ASC'
};
$items = $pdo->query($sql)->fetchAll();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="css/item-list.css">

<section class="section has-background-warning-light">
  <div class="container">

    <!-- ソートメニュー -->
    <div class="sort-bar">
      <div class="sort-center">
        <select id="sortSelect">
          <option value="price_asc">価格順（安い順）</option>
          <option value="price_desc">価格順（高い順）</option>
          <option value="id_desc">新着順</option>
        </select>
        <i class="fas fa-sort-amount-up"></i>
      </div>
    </div>

    <!-- 商品一覧 -->
    <div class="item-grid">
      <?php if (empty($items)): ?>
        <p>現在表示できる商品がありません。</p>
      <?php else:
        foreach ($items as $item): ?>
          <div class="item">
            <a href="item-detail.php?item_id=<?= htmlspecialchars($item['item_id']) ?>">
              <div class="image-box">
                <img src="item-image/no-image.png" alt="商品画像">
              </div>
            </a>
            <p><?= htmlspecialchars($item['item_name']) ?></p>
            <p>¥<?= number_format($item['price']) ?></p>
          </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
